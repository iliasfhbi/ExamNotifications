<?php
require_once __DIR__ . '/../../vendor/submodules/plugin-core-functions/autoloader/Autoloader.php';

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Control\ExamNotificationsGUI\CloseMessageCommand;
use HSBI\ExamNotifications\Control\ExamNotificationsGUI\DefineMessagesCommand;
use HSBI\ExamNotifications\Control\ExamNotificationsGUI\GetMessagesCommand;
use HSBI\PluginCoreFunctions\GUI\Class\Control\GUIClassCommandRouterImplementation;
use HSBI\PluginCoreFunctions\GUI\Class\Model\GUIClassCommandRouter;

/**
 * GUI class for setting a notification message for the participants of a test
 *
 * @package classes\GUI
 * @ilCtrl_isCalledBy ExamNotificationsGUI: ilUIPluginRouterGUI
 */
class ExamNotificationsGUI implements GUIClassCommandRouter
{
    use GUIClassCommandRouterImplementation;

    /**
     *
     * {@inheritdoc}
     * @see GUIClassCommandRouter::getCommands()
     */
    public function getCommands(): array
    {
        PLog::debug()->parameter();
        return PLog::debug()->return([
            new DefineMessagesCommand(),
            new GetMessagesCommand(),
            new CloseMessageCommand()
        ]);
    }
}