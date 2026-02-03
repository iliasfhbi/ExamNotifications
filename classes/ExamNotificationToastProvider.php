<?php
declare(strict_types = 1);

require_once __DIR__ . '/../vendor/submodules/plugin-core-functions/autoloader/Autoloader.php';

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use HSBI\ExamNotifications\Model\Message;
use HSBI\ExamNotifications\Model\MessageStatus;
use HSBI\ExamNotifications\Model\MessageViewed;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\PHPUtilities\CRUD\Model\ComparisonMode;
use HSBI\PHPUtilities\CRUD\Model\Fields;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\FilterOption;
use HSBI\PHPUtilities\CRUD\Model\SortDefinition;
use HSBI\PHPUtilities\CRUD\Model\SortDirection;
use HSBI\PHPUtilities\CRUD\Model\SortOption;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\Objects\Test\TestObject;
use ILIAS\GlobalScreen\Scope\Toast\Provider\AbstractToastProvider;
use HSBI\PluginCoreFunctions\PluginLoader;

// ./libs/composer/vendor/bin/composer dump-autoload
class ExamNotificationToastProvider extends AbstractToastProvider
{

    public const TOAST_PROVIDER_NAMESPACE = 'exam_notifications';

    /**
     *
     * {@inheritdoc}
     */
    public function getToasts(): array
    {
        PLog::debug()->parameter();
        if ($_SERVER['PHP_SELF'] === '/src/GlobalScreen/Client/notify.php')
            return PLog::debug()->return([]);

        $plugin = PluginLoader::getPluginLoader()->getPlugin();
        if (! $plugin->isActive())
            return PLog::debug()->return([]);

        $plugin = ilExamNotificationsPlugin::instance();
        $pluginConfigAccess = $plugin->getUiHookPluginInfo()->getPluginConfigAccess();

        if ($pluginConfigAccess->getValue(PluginConfigID::PRESENTATION_TYPE) !== PluginConfigID::PRESENTATION_TYPE_TOAST->name)
            return PLog::debug()->return([]);

        $messageDAO = new MessageDAO();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);

        $messageFilterDefinition = new FilterDefinition();
        $messageViewedFilterDefinition = new FilterDefinition();
        $sortDefinition = new SortDefinition(new SortOption($messageDAO->getTimestamp(), SortDirection::DESCENDING));

        $userID = PCFIDIC::User()->currentUserID();
        $userHasActiveTests = false;
        $userTests = [];

        foreach (PCFIDIC::Objects()->Test()->getAllTestObjectsOfUser($userID) as $refId => /** @var $testObject TestObject*/
        $testObject) {
            if ($testObject->hasUnfinishedPasses($userID)) {
                $userHasActiveTests = true;
                $userTests[$refId] = $testObject;
                $messageFilterDefinition->addFilterOption(new FilterOption($messageDAO->getTestReferenceId()
                    ->initField($refId)));
                $messageViewedFilterDefinition->addFilterOption(new FilterOption($messageViewedDAO->getTestReferenceId()
                    ->initField($refId)));
            }
        }

        if (! $userHasActiveTests)
            return PLog::debug()->return([]);

        $messageViewedFilterDefinition->addFilterOption(new FilterOption($messageViewedDAO->getReceiverUserId()
            ->initField($userID)));

        $now = new DateTimeImmutable();
        $refreshInterval = PCFIDIC::Settings()->lookupIntValue('notifications', 'osd_interval', 60000);
        $refreshInterval = ($refreshInterval / 1000) - 1;
        $viewedMessageEntryExists = [];
        foreach ($messageViewedDAO->read(filterDefinition: $messageViewedFilterDefinition) as /** @var $messageViewed MessageViewed */
        $messageViewed) {
            $dontShow = true;
            if ($messageViewed->getMessageStatus() == MessageStatus::TOAST) {
                $viewedMessageEntryExists[$messageViewed->getMessageId()] = true;
                $messageStatusTimestamp = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $messageViewed->getMessageStatusTimestamp());

                if ($now->getTimestamp() - $messageStatusTimestamp->getTimestamp() > $refreshInterval)
                    $messageViewedDAO->update(new Fields($messageViewedDAO->getMessageStatus()
                        ->initField(MessageStatus::TOASTED->value), $messageViewedDAO->getMessageStatusTimestamp()
                        ->initField($now->format('Y-m-d H:i:s'))), new FilterDefinition(new FilterOption($messageViewedDAO->getMessageId()
                        ->initField($messageViewed->getMessageId())), new FilterOption($messageViewedDAO->getReceiverUserId()
                        ->initField($messageViewed->getReceiverUserId()))));
                else
                    $dontShow = false;
            }
            if ($dontShow)
                $messageFilterDefinition->addFilterOption(new FilterOption($messageDAO->getId()
                    ->initField($messageViewed->getMessageId()), comparisonMode: ComparisonMode::NOT_EQUAL));
        }

        $messages = $messageDAO->read(sortDefinition: $sortDefinition, filterDefinition: $messageFilterDefinition);
        $toasts = [];
        $icon = $this->dic->ui()
            ->factory()
            ->symbol()
            ->icon()
            ->standard("tst", $plugin->txt('plugin_name'));
        foreach ($messages as /** @var $message Message*/
        $message) {
            if (! ($viewedMessageEntryExists[$message->getId()] ?? false))
                $messageViewedDAO->create(new MessageViewed(0, $message->getTestReferenceId(), $message->getId(), $userID, MessageStatus::TOAST, $now->format('Y-m-d H:i:s')));
            $toastID = self::TOAST_PROVIDER_NAMESPACE . '_' . $message->getId();
            $toast = $this->toast_factory->standard($this->if->identifier($toastID), // Every item needs an identification
                $plugin->txt('plugin_name') . " " . $userTests[$message->getTestReferenceId()]->getTitle()
            )
                ->withDescription($message->getText())
                ->
            // Use ilLanguage here!
            // ->withVanishTime(3000000); // 1000 = 1 second // Ab ILIAS 9.10 nicht mehr verfügbar!!!!
            withIcon($icon);
            $toasts[] = $toast;
        }
        return $toasts;
    }
}