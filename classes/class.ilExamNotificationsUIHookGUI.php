<?php

require_once __DIR__ . "/../vendor/autoload.php";

use ILIAS\DI\Container;

/**
 * User interface hook class responsible for injecting the user interfaces of the plugin into the overall ILIAS user interface
 *
 * @author Sebastian Otte <sebastian.otte@fh-bielefeld.de>
 * @ingroup ServicesUIComponent
 */
class ilExamNotificationsUIHookGUI extends ilUIHookPluginGUI
{
    /**
     * @var Container
     */
    private $dic;

    /**
     * @var ilExamNotificationsPlugin
     */
    private $plugin;

    /**
     * ilExamNotificationsUIHookGUI constructor.
     * @global $DIC
     */
    public function __construct()
    {
        global $DIC;

        $this->dic = $DIC;

        $this->plugin = new ilExamNotificationsPlugin();
    }

    /**
     * Modify HTML output of GUI elements.
     *
     * Modifications modes are:
     *
     * - ilUIHookPluginGUI::KEEP (No modification)
     * - ilUIHookPluginGUI::REPLACE (Replace default HTML with your HTML)
     * - ilUIHookPluginGUI::APPEND (Append your HTML to the default HTML)
     * - ilUIHookPluginGUI::PREPEND (Prepend your HTML to the default HTML)
     *
     * @param string $a_comp component
     * @param string $a_part string that identifies the part of the UI that is handled
     * @param array $a_par array of parameters (depend on $a_comp and $a_part)
     *
     * @return array array with entries "mode" => modification mode, "html" => your html
     */
    function getHTML($a_comp, $a_part, $a_par = array())
    {
        if ($_GET["cmdClass"] === "iltestparticipantsgui") {
            // place to add controls for setting a message
            $referenceId = $_GET["ref_id"];
            if ($a_par["tpl_id"] === "Services/Table/tpl.table2.html" && $a_part === "template_get") {
                $this->plugin->includeClass("GUI/class.SetMessageGUI.php");
                $setMessageGUI = new SetMessageGUI($referenceId);
                $html = $this->dic->ctrl()->getHTML($setMessageGUI);
                return array("mode" => ilUIHookPluginGUI::PREPEND, "html" => $html);
            }
        }

        if ($a_par && $a_par["tpl_id"] && strpos($a_par["tpl_id"], "Modules/Test/tpl.il_as_tst_output.html") === 0) {
            // place to inject content into the test page template
            $referenceId = $_GET["ref_id"];
            $this->plugin->includeClass("GUI/class.DisplayMessageGUI.php");
            $displayMessageGUI = new DisplayMessageGUI($referenceId);
            $html = $this->dic->ctrl()->getHTML($displayMessageGUI);
            return array("mode" => ilUIHookPluginGUI::PREPEND, "html" => $html);
        }

        return array("mode" => ilUIHookPluginGUI::KEEP, "html" => "");
    }

    /**
     * Modify GUI objects, before they generate output
     *
     * @param string $a_comp component
     * @param string $a_part string that identifies the part of the UI that is handled
     * @param array $a_par array of parameters (depend on $a_comp and $a_part)
     */
    function modifyGUI($a_comp, $a_part, $a_par = array())
    {
        // currently only implemented for $ilTabsGUI

        // tabs hook
        // note that you currently do not get information in $a_comp
        // here. So you need to use general GET/POST information
        // like $_GET["baseClass"], $ilCtrl->getCmdClass/getCmd
        // to determine the context.
    }
}