<?php
use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Control\ExamNotificationsGUI\DefineMessagesCommand;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\PluginCoreFunctions\MainMenu\Model\Entries;
use HSBI\PluginCoreFunctions\MainMenu\Model\Entry;
use HSBI\PluginCoreFunctions\MainMenu\Model\Link;
use HSBI\PluginCoreFunctions\PluginMainClass\UIHookPlugin;
use HSBI\PluginCoreFunctions\PluginMainClass\Registration\AdditionalGlobalJavaScriptRegistration;
use HSBI\PluginCoreFunctions\PluginMainClass\Registration\ContainingPCFPathRegistration;
use HSBI\PluginCoreFunctions\PluginMainClass\Registration\DAORegistration;
use HSBI\PluginCoreFunctions\PluginMainClass\Registration\MainMenuEntriesRegistration;
use HSBI\PluginCoreFunctions\PluginMainClass\Registration\PluginMigrationRegistration;
use HSBI\PluginCoreFunctions\UIComponents\UIIcon;
use HSBI\PluginCoreFunctions\UIComponents\Icon\IconSize;
use HSBI\PluginCoreFunctions\UIComponents\Icon\StandardIcon;
use HSBI\ExamNotifications\Control\Migration\Migration_From_1_1_0;
use HSBI\ExamNotifications\Control\Migration\Migration_From_9_0_0000;

require_once __DIR__ . '/../submodule/plugin-core-functions/autoloader/Autoloader.php';
require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Plugin main class of ExamNotifications
 */
class ilExamNotificationsPlugin extends UIHookPlugin
{

    /**
     *
     * {@inheritdoc}
     * @see ContainingPCFPathRegistration::registerContainingPCFPath()
     */
    protected function registerContainingPCFPath(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return(__DIR__ . '/../submodule/plugin-core-functions');
    }

    /**
     *
     * {@inheritdoc}
     * @see DAORegistration::registerDAOs()
     */
    protected function registerDAOs(): array
    {
        PLog::debug()->parameter();
        $messageDAO = new MessageDAO();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);
        return PLog::debug()->return([
            $messageDAO,
            $messageViewedDAO
        ]);
    }

    /**
     *
     * {@inheritdoc}
     * @see PluginMigrationRegistration::registerPluginMigrations()
     */
    protected function registerPluginMigrations(): array
    {
        PLog::debug()->parameter();
        return PLog::debug()->return([
            new Migration_From_1_1_0(),
            new Migration_From_9_0_0000()
        ]);
    }

    /**
     *
     * {@inheritdoc}
     * @see MainMenuEntriesRegistration::registerMainMenuEntries()
     */
    protected function registerMainMenuEntries(): Entries
    {
        PLog::debug()->parameter();

        $refID = PCFIDIC::CTRL()->getRefID();
        $entries = new Entries();
        if ($refID === null)
            return PLog::debug()->return($entries);
        else {
            // Gets the link to the ExamNotificationsGUI
            $target = PCFIDIC::CTRL()->getLinkTargetByClass(ExamNotificationsGUI::class, DefineMessagesCommand::getCmd());
            // passes the ref_id of the test to ExamNotificationsGUI
            // passes view_control/input_0/input_1=0&view_control/input_0/input_2=25&view_control/input_3/input_4=timestamp&view_control/input_3/input_5=DESC so that the messages are sorted after thier timestamp
            $target .= "&ref_id=$refID&view_control/input_0/input_1=0&view_control/input_0/input_2=25&view_control/input_3/input_4=timestamp&view_control/input_3/input_5=DESC";
            $link = new Link($target);
            $title = self::instance()->txt("plugin_name");
            $accessManagerClosure = PCFIDIC::Objects()->Test()->hasUserWritePermission(...);
            $uiIcon = new UIIcon(StandardIcon::ADMINISTRATIVE_NOTIFICATIONS, $title, IconSize::SMALL, false);
            $entry = new Entry($title, $link, $accessManagerClosure, $accessManagerClosure, $uiIcon);
            $entries->setEntries($entry);
            return PLog::debug()->return($entries);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see AdditionalGlobalJavaScriptRegistration::registerAdditionalGlobalJavaScripts()
     */
    protected function registerAdditionalGlobalJavaScripts(): array
    {
        PLog::debug()->parameter();
        $additionalGlobalJavaScripts = [];
        $pluginConfigAccess = self::instance()->getUiHookPluginInfo()->getPluginConfigAccess();
        if ($pluginConfigAccess->getValue(PluginConfigID::PRESENTATION_TYPE, PluginConfigID::PRESENTATION_TYPE_JS->name) === PluginConfigID::PRESENTATION_TYPE_JS->name)
            // Add/Update version add the end to ensure that the cached Js will be requested again on changes.
            $additionalGlobalJavaScripts[] = "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamNotifications/src/ExamNotificationV1.js";
        return PLog::debug()->return($additionalGlobalJavaScripts);
    }
}