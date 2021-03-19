<?php

use ExamNotifications\ConfigurationAccess;
use ExamNotifications\ConfigurationAccessInterface;
use ILIAS\DI\Container;

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * Plugin configuration GUI
 *
 * @author Sebastian Otte <sebastian.otte@fh-bielefeld.de>
 * @ingroup ServicesUIComponent
 */
class ilExamNotificationsConfigGUI extends ilPluginConfigGUI
{
    const CMD_SAVE_CONFIGURATION = "save";

    const POLLING_INTERVAL_MIN_VALUE = 5;
    const POLLING_INTERVAL_MAX_VALUE = 300;
    const PARAMETER_POLLING_INTERVAL = "form_input_2"; // setting a proper name would result in checking the post value after constructing the ui so the current value could not be pre-selected

    /**
     * @var Container
     */
    private $dic;

    private $tpl;

    /**
     * @var ilExamNotificationsPlugin
     */
    private $plugin;

    /**
     * @var ConfigurationAccessInterface
     */
    private $configurationAccess;

    function __construct()
    {
        global $DIC;
        $this->dic = $DIC;
        $this->tpl = $DIC["tpl"];

        $this->plugin = new ilExamNotificationsPlugin();
        $this->configurationAccess = new ConfigurationAccess();
    }

    /**
     * Handles all commands, default is "configure"
     *
     * @param
     *            $cmd
     */
    function performCommand($cmd)
    {
        switch ($cmd) {
            default:
                $this->displayConfigurationPanel();
        }
    }

    function displayConfigurationPanel()
    {
        $uiFactory = $this->dic->ui()->factory();
        $uiRenderer = $this->dic->ui()->renderer();

        $configurationPanelContent = [];

        $cmd = $this->dic->ctrl()->getCmd();
        if ($cmd === self::CMD_SAVE_CONFIGURATION && is_numeric($_POST[self::PARAMETER_POLLING_INTERVAL])) {
            $pollingInterval = $_POST[self::PARAMETER_POLLING_INTERVAL];

            if (self::POLLING_INTERVAL_MIN_VALUE <= $pollingInterval && $pollingInterval <= self::POLLING_INTERVAL_MAX_VALUE) {
                // polling interval is valid - save and display success message
                $this->configurationAccess->setPollingInterval($_POST[self::PARAMETER_POLLING_INTERVAL]);
                $configurationPanelContent[] = $uiFactory->messageBox()->success($this->plugin->txt("config_saved"));
            } else {
                // polling interval is out of bounds - display error
                $configurationPanelContent[] = $uiFactory->messageBox()->failure(sprintf($this->plugin->txt("config_pollingInterval_outOfBounds"), $pollingInterval));
            }
        }

        $pollingInterval = $this->configurationAccess->getPollingInterval();

        $pollingIntervalByline = sprintf($this->plugin->txt("config_pollingInterval_byline"), self::POLLING_INTERVAL_MIN_VALUE, self::POLLING_INTERVAL_MAX_VALUE); // unfortunately no support for min and max value attributes
        $inputs[] = $uiFactory->input()->field()->numeric($this->plugin->txt("config_pollingInterval_label"), $pollingIntervalByline)->withRequired(true)->withValue($pollingInterval);

        $sections[] = $uiFactory->input()->field()->section($inputs, $this->plugin->txt("config_pollingInterval_section"), $this->plugin->txt("config_pollingInterval_section_description"));

        $formPostUrl = $this->dic->ctrl()->getFormAction($this, self::CMD_SAVE_CONFIGURATION);
        $configurationPanelContent[] = $uiFactory->input()->container()->form()->standard($formPostUrl, $sections);

        $configurationPanel = $uiFactory->panel()->standard($this->plugin->txt("config_header"), $configurationPanelContent);
        $this->tpl->setContent($uiRenderer->render($configurationPanel));
    }
}