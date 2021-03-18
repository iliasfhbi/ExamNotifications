<?php

use ExamNotifications\MessagesAccess;
use ExamNotifications\MessagesAccessInterface;
use ExamNotifications\MessageTypes;
use ExamNotifications\NotificationMessage;
use ILIAS\DI\Container;

/**
 * Class SetMessageGUI
 * @ilCtrl_isCalledBy SetMessageGUI: ilTestParticipantsTableGUI
 * @ilCtrl_Calls SetMessageGUI: CurrentMessagePreviewGUI
 */
class SetMessageGUI
{
    const PARAMETER_MESSAGE_TEXT = "messageText";
    const PARAMETER_MESSAGE_TYPE = "messageType";
    const PARAMETER_RESET_MESSAGE = "reset";

    /**
     * @var Container
     */
    private $dic;

    /**
     * @var ilExamNotificationsPlugin
     */
    private $plugin;

    /**
     * @var ilObjTest
     */
    private $testObject;

    /**
     * @var MessagesAccessInterface
     */
    private $messagesAccess;

    public function __construct(int $testObjectRefId)
    {
        global $DIC;
        $this->dic = $DIC;

        $this->plugin = new ilExamNotificationsPlugin();

        $this->testObject = new ilObjTest($testObjectRefId);
        $this->messagesAccess = new MessagesAccess();
        $this->plugin->includeClass("GUI/class.CurrentMessagePreviewGUI.php");
    }

    /**
     * @return string
     * @throws ilTemplateException
     */
    public function getHTML(): string
    {
        // make sure the user is allowed to write this object
        $accessHandler = $this->dic->access();
        if (!$accessHandler->checkAccess("write", "", $this->testObject->getRefId(), $this->testObject->getType(), $this->testObject->getId())) {
            // user is not allowed to edit the test object
            $ilErr = $this->dic['ilErr'];
            $lng = $this->dic['lng'];

            $ilErr->raiseError($lng->txt('permission_denied'), $ilErr->WARNING);
        }

        $uiFactory = $this->dic->ui()->factory();
        $uiRenderer = $this->dic->ui()->renderer();

        $currentMessage = null;
        $successMessageControl = null;

        if (isset($_POST[self::PARAMETER_MESSAGE_TEXT])) {
            // save message text to database and display success message
            $currentMessageText = $_POST[self::PARAMETER_MESSAGE_TEXT];
            $currentMessage = new NotificationMessage($currentMessageText, MessageTypes::DANGER);

            $this->messagesAccess->setMessageForTest($this->testObject->getId(), $currentMessage);
            $successMessageControl = $uiFactory->messageBox()->success($this->plugin->txt("setMessage_messageSet"));
        } elseif (isset($_POST[self::PARAMETER_RESET_MESSAGE])) {
            // reset message and display success message
            $this->messagesAccess->setMessageForTest($this->testObject->getId(), new NotificationMessage(""));
            $successMessageControl = $uiFactory->messageBox()->success($this->plugin->txt("setMessage_messageReset"));
        } else {
            // get current text from database
            $currentMessage = $this->messagesAccess->getMessageForTest($this->testObject->getId());
        }

        $panelContent = [];

        if ($currentMessage && $currentMessage->getText()) {
            $previewPanelContent = [];
            // add message preview
            $currentMessagePreviewGUI = new CurrentMessagePreviewGUI($currentMessage);
            $previewPanelContent[] = $uiFactory->legacy($currentMessagePreviewGUI->getHTML());
            // add reset message form
            $resetMessageFormTemplate = $this->plugin->getTemplate("tpl.resetMessageForm.html");
            $resetMessageFormTemplate->setVariable("SUBMIT", $this->plugin->txt("setMessage_message_reset"));
            $previewPanelContent[] = $uiFactory->legacy($resetMessageFormTemplate->get());
            // add sub panel to panel
            $panelContent[] = $uiFactory->panel()->sub($this->plugin->txt("setMessage_preview_header"), $previewPanelContent);
        }

        $formTemplate = $this->plugin->getTemplate("tpl.setMessageForm.html"); // todo: change to 5.4 forms

        $formTemplate->setVariable("MESSAGE_TEXT_LABEL", $this->plugin->txt("setMessage_messageText_label"));
        $formTemplate->setVariable("MESSAGE_TEXT_BYLINE", sprintf($this->plugin->txt("setMessage_messageText_byline"), NotificationMessage::MAXIMUM_LENGTH));
        $formTemplate->setVariable("MESSAGE_SUBMIT", $this->plugin->txt("setMessage_message_submit"));
        $panelContent[] = $uiFactory->panel()->sub($this->plugin->txt("setMessage_message_header"), $uiFactory->legacy($formTemplate->get()));

        $uiComponents = [];
        if ($successMessageControl) {
            // add success message as first control if it is used
            array_push($uiComponents, $successMessageControl);
        }

        $uiComponents[] = $uiFactory->panel()->standard($this->plugin->txt("setMessage_header"), $panelContent);
        return $uiRenderer->render($uiComponents);
    }
}