<?php
namespace HSBI\ExamNotifications\Control\ilExamNotificationsConfigGUI;

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Command;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Result;
use HSBI\PluginCoreFunctions\GUI\Command\Model\UIComponentResult;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\UIInputForm;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\UIInputRadio;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\Model\RadioOption;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\Model\RadioOptions;
use ilExamNotificationsPlugin;
/**
 * The controller for the ConfigCommand of ilExamNotificationsConfigGUI
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\Control\ilExamNotificationsConfigGUI
 *         
 * @see ilExamNotificationsConfigGUI
 */
class ConfigCommand implements Command
{
    
    /**
     *
     * {@inheritdoc}
     * @see Command::run()
     */
    public function run(): Result
    {
        PLog::debug()->parameter();        
        $plugin = ilExamNotificationsPlugin::instance();
        $pluginConfigAccess = $plugin->getUiHookPluginInfo()->getPluginConfigAccess();
        
        $form = new UIInputForm('pluginConfig', $plugin->txt('plugin_configuration'));
        
        $radioOptions = new RadioOptions();
        $radioOptions->addRadioOptions(new RadioOption(PluginConfigID::PRESENTATION_TYPE_JS, $plugin->txt('banner_embedded_via_JavaScript')));
        $radioOptions->addRadioOptions(new RadioOption(PluginConfigID::PRESENTATION_TYPE_TOAST, $plugin->txt('via_toasts_and_notifications')));
        $presentationType = new UIInputRadio(PluginConfigID::PRESENTATION_TYPE, $plugin->txt('notification_display_mode'), $radioOptions);
        $presentationType->setValue($pluginConfigAccess->getValue(PluginConfigID::PRESENTATION_TYPE, PluginConfigID::PRESENTATION_TYPE_JS->name));
        $form->addUIInputs($presentationType);
        
        $presentationTypeResult = $form->getFormResults()->getFormResult(PluginConfigID::PRESENTATION_TYPE);
        if($presentationTypeResult !== null)
            $pluginConfigAccess->setValue(PluginConfigID::PRESENTATION_TYPE, $presentationTypeResult);
  
        return PLog::debug()->return(new UIComponentResult($form));
    }

    /**
     *
     * {@inheritdoc}
     * @see Command::getCmd()
     */
    public static function getCmd(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return('configure');
    }
}

