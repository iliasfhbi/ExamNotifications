<?php
namespace HSBI\ExamNotifications\Control\ilExamNotificationsConfigGUI;

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\PluginCoreFunctions\Config\Plugin\Control\PluginConfigAccess;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Command;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Result;
use HSBI\PluginCoreFunctions\GUI\Command\Model\UIComponentResult;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\UIInputForm;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\UIInputRadio;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\Model\RadioOption;
use HSBI\PluginCoreFunctions\UIComponents\UIInput\Model\RadioOptions;
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
        $pluginConfigAccess = new PluginConfigAccess();
        
        $form = new UIInputForm('pluginConfig', "Plugin Konfiguration");
        
        $radioOptions = new RadioOptions();
        $radioOptions->addRadioOptions(new RadioOption(PluginConfigID::PRESENTATION_TYPE_JS, 'Mittels Javascript eingebettetes Banner'));
        $radioOptions->addRadioOptions(new RadioOption(PluginConfigID::PRESENTATION_TYPE_TOAST, 'Mittels Toasts und Notifications'));
        $presentationType = new UIInputRadio(PluginConfigID::PRESENTATION_TYPE, 'Anzeigeart der Benachrichtigungen', $radioOptions);
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

