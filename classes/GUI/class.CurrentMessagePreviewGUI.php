<?php

use ExamNotifications\NotificationMessage;
use ILIAS\DI\Container;

/**
 * GUI class for rendering a preview of a notification message
 */
class CurrentMessagePreviewGUI
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
     * @var NotificationMessage
     */
    private $message;

    /**
     * CurrentMessagePreviewGUI constructor.
     * @param NotificationMessage $message
     * @global $DIC
     */
    public function __construct(NotificationMessage $message)
    {
        global $DIC;
        $this->dic = $DIC;

        $this->plugin = new ilExamNotificationsPlugin();

        $this->message = $message;
    }

    /**
     * @return string
     * @throws ilTemplateException
     */
    public function getHTML(): string {
        // use custom template instead of message box control to mimic the behaviour of the recurrently executed script
        $template = $this->plugin->getTemplate("tpl.messagePreview.html");

        // set alert type
        switch($this->message->getType()){
            case 0:
                $alertType = "info";
                break;
            case 1:
                $alertType = "warning";
                break;
            default:
                $alertType = "danger";
        }

        $template->setVariable("ALERT_TYPE", $alertType);

        // format alert title
        $alertTitle = sprintf("%s - %s", $this->message->getSender()->getFullname(), $this->message->getTimestamp()->format("H:i:s"));
        $template->setVariable("ALERT_TITLE", $alertTitle);

        // replace curly braces in preview with html codes because otherwise text enclosed in curly braces is treated as a template variable
        $messageText = $this->message->getText();
        $messageText = htmlspecialchars($messageText);
        $messageText = str_replace("{", "&lcub;", $messageText);
        $messageText = str_replace("}", "&rcub;", $messageText);

        $template->setVariable("ALERT_TEXT", $messageText); // escape special characters in case someone enters html or javascript code

        return $template->get();
    }
}