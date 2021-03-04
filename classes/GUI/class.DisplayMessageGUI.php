<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use ExamNotifications\MessagesAccess;
use ExamNotifications\MessagesAccessInterface;
use ILIAS\DI\Container;

/**
 * Class DisplayMessageGUI
 * @ilCtrl_isCalledBy DisplayMessageGUI: ilTestPlayerFixedQuestionSetGUI
 * @ilCtrl_isCalledBy DisplayMessageGUI: ilUIPluginRouterGUI
 */
class DisplayMessageGUI
{
    const CMD_GET_MESSAGE = "getMsg";
    const REQUEST_MESSAGE_INTERVAL_IN_SECONDS = 30;

    /**
     * @var Container
     */
    private $dic;

    /**
     * @var ilObject
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
        $messageContainerTemplate->setVariable("DUMMY"); // without setting a variable, the html does not get parsed
        $output = $messageContainerTemplate->get();

        // setup script
        $scriptTemplate = $this->plugin->getTemplate("tpl.messageRequest.js");
        $url = $this->dic->ctrl()->getLinkTargetByClass(["ilUIPluginRouterGUI", "DisplayMessageGUI"], self::CMD_GET_MESSAGE) . "&ref_id=" . $this->testObject->getRefId();
        $scriptTemplate->setVariable("URL", $url);
        $scriptTemplate->setVariable("REQUEST_INTERVAL_IN_SECONDS", self::REQUEST_MESSAGE_INTERVAL_IN_SECONDS);
        $output .= "<script>" . $scriptTemplate->get() . "</script>";
        $styleSheetLocation = $this->plugin->getStyleSheetLocation("displayMessage.css");
        $output .= "<link rel='stylesheet' href='$styleSheetLocation'/>";

        return $output;
    }

    public function executeCommand() {
        $cmd = $this->dic->ctrl()->getCmd();

        if($cmd === self::CMD_GET_MESSAGE) {
            // request for current message - return nothing but plain utf-8 text
            header('Content-type: text/plain;charset=UTF-8');
            echo htmlspecialchars($this->messagesAccess->getMessageForTest($this->testObject->getId())); // escape special characters in message text
            exit;
        }
    }
}