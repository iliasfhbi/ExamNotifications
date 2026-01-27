<?php
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use HSBI\ExamNotifications\Model\Message;
use HSBI\ExamNotifications\Model\MessageStatus;
use HSBI\ExamNotifications\Model\MessageViewed;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\PHPUtilities\CRUD\Model\ComparisonMode;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\FilterOption;
use HSBI\PHPUtilities\CRUD\Model\SortDefinition;
use HSBI\PHPUtilities\CRUD\Model\SortDirection;
use HSBI\PHPUtilities\CRUD\Model\SortOption;
use ILIAS\GlobalScreen\Scope\Notification\Provider\AbstractNotificationProvider;
use ILIAS\GlobalScreen\Scope\Notification\Provider\NotificationProvider;
use HSBI\ExamNotifications\PLog;
use HSBI\PHPUtilities\CRUD\Model\Fields;
use HSBI\PluginCoreFunctions\PluginLoader;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\Objects\Test\TestObject;

require_once __DIR__ . "/../vendor/composer-vendor/autoload.php";
require_once __DIR__ . '/../vendor/submodules/plugin-core-functions/autoloader/Autoloader.php';

class ExamNotificationProvider extends AbstractNotificationProvider implements NotificationProvider
{

    /**
     *
     * {@inheritdoc}
     */
    public function getNotifications(): array
    {
        PLog::debug()->parameter();

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

        $factory = $this->globalScreen()
            ->notifications()
            ->factory();

        $icon = $this->dic->ui()
            ->factory()
            ->symbol()
            ->icon()
            ->standard("tst", $plugin->txt('plugin_name'));

        $group = $factory->standardGroup($this->if->identifier('exam_notification_group'))
            ->withTitle("{$plugin->txt('exam_notifications')}:");

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

        $messageStates = [];
        foreach ($messageViewedDAO->read(filterDefinition: $messageViewedFilterDefinition) as /** @var $messageViewed MessageViewed */
        $messageViewed) {
            $messageStatus = $messageViewed->getMessageStatus();
            $messageStates[$messageViewed->getMessageId()] = $messageStatus;
            if ($messageStatus === MessageStatus::CLOSED)
                $messageFilterDefinition->addFilterOption(new FilterOption($messageDAO->getId()
                    ->initField($messageViewed->getMessageId()), comparisonMode: ComparisonMode::NOT_EQUAL));
        }

        $messages = $messageDAO->read(sortDefinition: $sortDefinition, filterDefinition: $messageFilterDefinition);

        if (sizeof($messages) == 0) {

            $examNotificationEmpty = $this->dic->ui()
                ->factory()
                ->item()
                ->notification($plugin->txt('no_new_messages'), $icon);

            $examNotificationEmptyItem = $factory->standard($this->if->identifier('test_notification_empty'))
                ->withNotificationItem($examNotificationEmpty)
                ->withNewAmount();

            $group = $group->addNotification($examNotificationEmptyItem);
            return PLog::debug()->return([
                $group
            ]);
        }
        
        $cmdClass = PCFIDIC::CTRL()->getCmdClass();
        
        $isCmdClassTestPlayer = $cmdClass === 'iltestplayerrandomquestionsetgui' || $cmdClass === 'iltestplayerfixedquestionsetgui' || $cmdClass === 'iltestscreengui';
        $thisRef = null;
        if ($isCmdClassTestPlayer)
            $thisRef = PCFIDIC::CTRL()->getRefID();
            else {
                $refCmdClass = PCFIDIC::CTRL()->HTTPReferer()->getCmdClass();
                $isCmdClassTestPlayer = $refCmdClass === 'iltestplayerrandomquestionsetgui' || $refCmdClass === 'iltestplayerfixedquestionsetgui' || $refCmdClass === 'iltestscreengui';
                $thisRef = PCFIDIC::CTRL()->HTTPReferer()->getRefID();
            }

        $unreadMessages = $group->getOldNotificationsCount();
        foreach ($messages as /** @var $message Message*/
        $message) {
            $messageStatus = $messageStates[$message->getId()] ?? null;
            if ($messageStatus !== MessageStatus::VIEWED)
                $unreadMessages ++;
            $testRef = $message->getTestReferenceId();
            $title = $userTests[$message->getTestReferenceId()]->getTitle();
            if (! ($isCmdClassTestPlayer && $thisRef === $testRef))
                $title = $this->dic->ui()
                    ->factory()
                    ->link()
                    ->standard($title, "ilias.php?baseClass=ilrepositorygui&cmdClass=ilTestScreenGUI&cmd=testScreen&ref_id=$testRef")
                    ->withOpenInNewViewport(true);

            $examNotification = $this->dic->ui()
                ->factory()
                ->item()
                ->notification($title, $icon)
                ->withDescription($message->getText())
                ->withProperties([
                    $plugin->txt('sent_at') => DateTime::createFromFormat('Y-m-d H:i:s', $message->getTimestamp())->format($plugin->txt('date_time_format'))
            ]);

            $examNotificationItem = $factory->standard($this->if->identifier('test_notification_' . $message->getId()))
                ->withNotificationItem($examNotification)
                ->withClosedCallable(function () use ($messageStatus, $messageViewedDAO, $userID, $message) {

                // @Todo: Memories, that those notifications have been closed.
                if ($messageStatus === null)
                    $messageViewedDAO->create(new MessageViewed(0, $message->getTestReferenceId(), $message->getId(), $userID, MessageStatus::CLOSED, date('Y-m-d H:i:s')));
                else
                    $messageViewedDAO->update(new Fields($messageViewedDAO->getMessageStatus()
                        ->initField(MessageStatus::CLOSED->value), $messageViewedDAO->getMessageStatusTimestamp()
                        ->initField(date('Y-m-d H:i:s'))), new FilterDefinition(new FilterOption($messageViewedDAO->getMessageId()
                        ->initField($message->getId())), new FilterOption($messageViewedDAO->getReceiverUserId()
                        ->initField($userID))));
            })
                ->withOpenedCallable(function () use ($messageStatus, $messageViewedDAO, $userID, $message) {
                // @Todo: Memories, that those notifications have been seen.
                if ($messageStatus === null)
                    $messageViewedDAO->create(new MessageViewed(0, $message->getTestReferenceId(), $message->getId(), $userID, MessageStatus::VIEWED, date('Y-m-d H:i:s')));
                else
                    $messageViewedDAO->update(new Fields($messageViewedDAO->getMessageStatus()
                        ->initField(MessageStatus::VIEWED->value), $messageViewedDAO->getMessageStatusTimestamp()
                        ->initField(date('Y-m-d H:i:s'))), new FilterDefinition(new FilterOption($messageViewedDAO->getMessageId()
                        ->initField($message->getId())), new FilterOption($messageViewedDAO->getReceiverUserId()
                        ->initField($userID))));
            })
                ->withNewAmount($unreadMessages);

            $group = $group->addNotification($examNotificationItem);
        }
        return [
            $group
        ];
    }
}