<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . '/../submodule/plugin-core-functions/autoloader/Autoloader.php';

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Control\ilExamNotificationsConfigGUI\ConfigCommand;
use HSBI\PluginCoreFunctions\GUI\Class\ConfigGUIClass;
use HSBI\PluginCoreFunctions\GUI\Class\Model\GUIClassCommandRouter;

/**
 * Plugin configuration GUI
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * 
 * @ingroup ServicesUIComponent
 * @ilCtrl_IsCalledBy ilExamNotificationsConfigGUI: ilObjComponentSettingsGUI
 */
class ilExamNotificationsConfigGUI extends ConfigGUIClass
{

    /**
     * {@inheritDoc}
     * @see GUIClassCommandRouter::getCommands()
     */
    public function getCommands(): array
    {
        PLog::debug()->parameter();
        return PLog::debug()->return([
            new ConfigCommand()
        ]);
    }

}