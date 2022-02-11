<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use ExamNotifications\ConfigurationAccess;
use ExamNotifications\ConfigurationAccessInterface;
use ExamNotifications\MessagesAccess;
use ExamNotifications\MessagesAccessInterface;
use ILIAS\DI\Container;

/**
 * GUI class for displaying notification messages to test participants
 *
 * @ilCtrl_isCalledBy DisplayMessageGUI: ilTestPlayerFixedQuestionSetGUI, ilTestPlayerRandomQuestionSetGUI
 * @ilCtrl_isCalledBy DisplayMessageGUI: ilUIPluginRouterGUI
 */
class DisplayMessageGUI
{
    /**
     * @var string
     */
    const CMD_GET_MESSAGE = "getMsg";

    /**
     * @var Container
     */
    private $dic;

    /**
     * @var ilObjTest
     */
    private $testObject;

    /**
     * @var ilExamNotificationsPlugin
     */
    private $plugin;

    /**
     * @var MessagesAccessInterface
     */
    private $messagesAccess;

    /**
     * @var ConfigurationAccessInterface
     */
    private $configurationAccess;

    /**
     * DisplayMessageGUI constructor.
     * @param int $testObjectRefId if not provided, value of $_GET["ref_id"] is used
     * @global $DIC
     */
    public function __construct(int $testObjectRefId = 0)
    {
        global $DIC;
        $this->dic = $DIC;

        if(!$testObjectRefId) {
            // look for ref_id parameter in case of ajax call
            if(isset($_GET["ref_id"]) && is_numeric($_GET["ref_id"])) {
                $testObjectRefId = $_GET["ref_id"];
            } else {
                // no reference id provided
                $ilErr = $this->dic['ilErr'];
                $lng = $this->dic['lng'];
                $ilErr->raiseError($lng->txt('permission_denied'), $ilErr->WARNING);
            }
        }

        $this->testObject = new ilObjTest($testObjectRefId);

        $this->plugin = new ilExamNotificationsPlugin();
        $this->messagesAccess = new MessagesAccess();
        $this->configurationAccess = new ConfigurationAccess();
    }

    /**
     * @return string
     * @throws ilTemplateException
     */
    public function getHTML(): string
    {
        // make sure the user is allowed to read this object
        $accessHandler = $this->dic->access();
        if(!$accessHandler->checkAccess("read", "", $this->testObject->getRefId(), $this->testObject->getType(), $this->testObject->getId())) {
            // user is not allowed to read the test object
            $ilErr = $this->dic['ilErr'];
            $lng = $this->dic['lng'];

            $ilErr->raiseError($lng->txt('permission_denied'), $ilErr->WARNING);
        }

        // setup message container
        $messageContainerTemplate = $this->plugin->getTemplate("tpl.displayMessageContainer.html");
        $messageContainerTemplate->setVariable("cssClasses", $this->getContainerCssClasses());
        $output = $messageContainerTemplate->get();

        // setup script
        $scriptTemplate = $this->plugin->getTemplate("tpl.messageRequest.js");
        $url = $this->dic->ctrl()->getLinkTargetByClass(["ilUIPluginRouterGUI", "DisplayMessageGUI"], self::CMD_GET_MESSAGE) . "&ref_id=" . $this->testObject->getRefId();
        $scriptTemplate->setVariable("URL", $url);
        $pollingInterval = $this->configurationAccess->getPollingInterval();
        $scriptTemplate->setVariable("REQUEST_INTERVAL_IN_SECONDS", $pollingInterval);
        $output .= "<script>" . $scriptTemplate->get() . "</script>";
        $styleSheetLocation = $this->plugin->getStyleSheetLocation("displayMessage.css");
        $output .= "<link rel='stylesheet' href='$styleSheetLocation'/>";

        return $output;
    }

    public function executeCommand() {
        $cmd = $this->dic->ctrl()->getCmd();

        if($cmd === self::CMD_GET_MESSAGE) {
            // request for current message - return message as json
            header('Content-type: application/json;charset=UTF-8');

            $message = $this->messagesAccess->getMessageForTest($this->testObject->getId());

            // check if message has been set yet
            if($message) {
                // messages has been set, return its components
                $response = [
                    "text" => htmlspecialchars($message->getText()), // escape special characters in message text
                    "type" => $message->getType(),
                    "sender" => $message->getSender()->getFullname(),
                    "timestamp" => $message->getTimestamp()->format("c") // ISO 8601 date
                ];
            } else {
                // no message has been set yet
                $response = [];
            }

            echo json_encode($response);
            exit;
        }
    }

    private function getContainerCssClasses() : string {
        $classes = "";
        // workaround for ILIAS 5.4: make css compatible by adding a class
        if(version_compare(ILIAS_VERSION_NUMERIC, "6.0") < 0) {
            $classes .= "ui-uihk-exnot-ilias54";
        }
        // add class if kiosk mode is enabled to adjust padding
        if($this->testObject->getKioskMode()) {
            $classes .= " ui-uihk-exnot-kiosk";
        }

        return trim($classes);
    }
}