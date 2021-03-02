<?php

use ExamNotifications\MessagesAccess;
use ExamNotifications\MessagesAccessInterface;
use ILIAS\DI\Container;

/**
 * Class SetMessageGUI
 * @ilCtrl_isCalledBy SetMessageGUI: ilTestParticipantsGUI
 */
class SetMessageGUI
{
    const PARAMETER_MESSAGE_TEXT = "messageText";

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
    }

    /**
     * @return string
     * @throws ilTemplateException
     */
    public function getHTML(): string
    {
        // make sure the user is allowed to write this object
        $accessHandler = $this->dic->access();
        if(!$accessHandler->checkAccess("write", "", $this->testObject->getRefId(), $this->testObject->getType(), $this->testObject->getId())) {
            // user is not allowed to edit the test object
            $ilErr = $this->dic['ilErr'];
            $lng = $this->dic['lng'];

            $ilErr->raiseError($lng->txt('permission_denied'), $ilErr->WARNING);
        }

        $uiFactory = $this->dic->ui()->factory();
        $uiRenderer = $this->dic->ui()->renderer();

        $currentMessageText = null;
        $successMessageControl = null;

        if (isset($_POST[self::PARAMETER_MESSAGE_TEXT])) {
            // save message text to database and display success message
            $currentMessageText = htmlspecialchars($_POST[self::PARAMETER_MESSAGE_TEXT]); // escape special characters in case someone enters html or javascript code
            $this->messagesAccess->setMessageForTest($this->testObject->getId(), $currentMessageText);
            $successMessageControl = $uiFactory->legacy("<p class='alert alert-success'>" . $this->plugin->txt("setMessage_messageSet") . "</p>");

        } else {
            $currentMessageText = $this->messagesAccess->getMessageForTest($this->testObject->getId());
        }


        $panelContent = [];
        $formTemplate = $this->plugin->getTemplate("tpl.setMessageForm.html");

        $formTemplate->setVariable("ACTION");
        $formTemplate->setVariable("MESSAGE_TEXT_LABEL", $this->plugin->txt("setMessage_messageText_label"));
        $formTemplate->setVariable("MESSAGE_TEXT_VALUE", $currentMessageText);
        $formTemplate->setVariable("MESSAGE_TEXT_SUBMIT", $this->plugin->txt("setMessage_messageText_submit"));
        $panelContent[] = $uiFactory->legacy($formTemplate->get());

        $uiComponents = [];
        if ($successMessageControl) {
            // add success message as first control if it is used
            array_push($uiComponents, $successMessageControl);
        }

        $uiComponents[] = $uiFactory->panel()->standard($this->plugin->txt("setMessage_header"), $panelContent);
        return $uiRenderer->render($uiComponents);
    }
}