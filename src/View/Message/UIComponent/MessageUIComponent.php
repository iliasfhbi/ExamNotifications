<?php
namespace HSBI\ExamNotifications\View\Message\UIComponent;

use HSBI\PluginCoreFunctions\UIComponents\UILink;
use ilExamNotificationsPlugin;
use HSBI\ExamNotifications\PLog;
use HSBI\PluginCoreFunctions\UIComponents\UILegacyContent;
use HSBI\PluginCoreFunctions\UIComponents\UIMessagebox;
use HSBI\PluginCoreFunctions\UIComponents\MessageBox\FailureMessage;

/**
 * Represents a MessageUIComponent
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\View\Message\UIComponent
 */
class MessageUIComponent extends UILegacyContent
{

    /**
     * The $messageID of this MessageUIComponent
     *
     * @var int
     */
    private int $messageID;

    /**
     * The $content of this MessageUIComponent
     *
     * @var string
     */
    private string $content;

    /**
     * The $timestamp of this MessageUIComponent
     *
     * @var string
     */
    private string $timestamp;

    /**
     * The $testTitle of this MessageUIComponent
     *
     * @var string
     */
    private string $testTitle;

    /**
     * The $testLink of this MessageUIComponent
     *
     * @var string|null
     */
    private ?string $testLink;

    /**
     * Constructs an new instance of MessageUIComponent.
     *
     * @param int $messageID
     *            The $messageID of the new MessageUIComponent
     * @param string $content
     *            The $content of the new MessageUIComponent
     * @param string $timestamp
     *            The $timestamp of the new MessageUIComponent
     * @param string $testTitle
     *            The $testTitle of the new MessageUIComponent
     * @param string|null $testLink
     *            The $testLink of the new MessageUIComponent
     */
    public function __construct(int $messageID, string $content, string $timestamp, string $testTitle, ?string $testLink)
    {
        PLog::debug()->parameter();
        $this->messageID = $messageID;
        $this->content = $content;
        $this->timestamp = $timestamp;
        $this->testTitle = $testTitle;
        $this->testLink = $testLink;
        parent::__construct($this->buildUILegacyContent(...));
        PLog::debug()->return();
    }

    /**
     * Gets the $messageID of this MessageUIComponent.
     *
     * @return int
     */
    public function getMessageID(): int
    {
        PLog::debug()->parameter();
        return PLog::debug()->return($this->messageID);
    }

    /**
     * Sets the $messageID of this MessageUIComponent.
     *
     * @param int $messageID
     *            The new $messageID of this MessageUIComponent
     */
    public function setMessageID(int $messageID): void
    {
        PLog::debug()->parameter();
        $this->messageID = $messageID;
        PLog::debug()->return();
    }

    /**
     * Gets the $content of this MessageUIComponent.
     *
     * @return string
     */
    public function getContent(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return($this->content);
    }

    /**
     * Sets the $content of this MessageUIComponent.
     *
     * @param string $content
     *            The new $content of this MessageUIComponent
     */
    public function setContent(string $content): void
    {
        PLog::debug()->parameter();
        $this->content = $content;
        PLog::debug()->return();
    }

    /**
     * Gets the $timestamp of this MessageUIComponent.
     *
     * @return string
     */
    public function getTimestamp(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return($this->timestamp);
    }

    /**
     * Sets the $timestamp of this MessageUIComponent.
     *
     * @param string $timestamp
     *            The new $timestamp of this MessageUIComponent
     */
    public function setTimestamp(string $timestamp): void
    {
        PLog::debug()->parameter();
        $this->timestamp = $timestamp;
        PLog::debug()->return();
    }

    /**
     * Gets the $testTitle of this MessageUIComponent.
     *
     * @return string
     */
    public function getTestTitle(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return($this->testTitle);
    }

    /**
     * Sets the $testTitle of this MessageUIComponent.
     *
     * @param string $testTitle
     *            The new $testTitle of this MessageUIComponent
     */
    public function setTestTitle(string $testTitle): void
    {
        PLog::debug()->parameter();
        $this->testTitle = $testTitle;
        PLog::debug()->return();
    }

    /**
     * Gets the $testLink of this MessageUIComponent.
     *
     * @return string|null
     */
    public function getTestLink(): ?string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return($this->testLink);
    }

    /**
     * Sets the $testLink of this MessageUIComponent.
     *
     * @param string|null $testLink
     *            The new $testLink of this MessageUIComponent
     */
    public function setTestLink(?string $testLink): void
    {
        PLog::debug()->parameter();
        $this->testLink = $testLink;
        PLog::debug()->return();
    }

    /**
     * Builds the HTML for the close button of this MessageUIComponent
     *
     * @return string
     */
    private function buildCloseButtonHTML(): string
    {
        PLog::debug()->parameter();
        $examNotificationID = $this->messageID;
        $closeButtonID = "ui_uihk_exnot_closeButton_ExamNotificationID_$examNotificationID";
        $closeMethod = 'ui_uihk_exnot_closeExamNotification'; // See src/ExamNotification.js
        $closeButton = "<button type='button' class='close' aria-label='Schließen' id='$closeButtonID' onclick='$closeMethod(\"$closeButtonID\");'>
        <span aria-hidden='true'>×</span>
        </button>";
        return PLog::debug()->return($closeButton);
    }

    /**
     * Builds the HTML for the title paragraph of this MessageUIComponent
     *
     * @return string
     */
    private function buildTitleParagraphHTML(): string
    {
        PLog::debug()->parameter();
        $plugin = ilExamNotificationsPlugin::instance();
        $title = "<p><b>{$plugin->txt('plugin_name')} - {$this->testTitle}</b></p>";
        return PLog::debug()->return($title);
    }

    /**
     * Builds the HTML for the content paragraph of this MessageUIComponent
     *
     * @return string
     */
    private function buildContentParagraphHTML(): string
    {
        PLog::debug()->parameter();
        $content = str_replace([
            "\t",
            "\n"
        ], [
            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ',
            '<br/>'
        ], $this->content);
        return PLog::debug()->return("<p>$content</p>");
    }

    /**
     * Builds the HTML for the timestamp section of this MessageUIComponent
     *
     * @return string
     */
    private function buildTimestampSectionHTML(): string
    {
        PLog::debug()->parameter();
        $plugin = ilExamNotificationsPlugin::instance();
        $timestamp = \DateTime::createFromFormat('Y-m-d H:i:s', $this->timestamp)->format($plugin->txt('date_time_format'));
        $timestamp = "{$plugin->txt('sent_at')}: <span>$timestamp</span>";
        return PLog::debug()->return($timestamp);
    }

    /**
     * Builds the UIComponent for the timestamp section of this MessageUIComponent
     *
     * @return UILink
     */
    private function buildTestLinkUIComponent(): UILink
    {
        PLog::debug()->parameter();
        $plugin = ilExamNotificationsPlugin::instance();
        $testLink = new UILink($plugin->txt('view_test_in_new_tab'), $this->testLink, openInNewTabFlag: true);
        return PLog::debug()->return($testLink);
    }

    /**
     * Builds the UIComponent for the footer section of this MessageUIComponent
     *
     * @return UILegacyContent
     */
    private function buildFooterUIComponent(): UILegacyContent
    {
        PLog::debug()->parameter();
        $footer = [];
        $footer[] = '<hr/><p>';
        $footer[] = $this->buildTimestampSectionHTML();
        if ($this->testLink !== null) {
            $footer[] = ' |&nbsp;';
            $footer[] = $this->buildTestLinkUIComponent();
        }
        $footer[] = '</p>';
        return PLog::debug()->return(new UILegacyContent(...$footer));
    }

    /**
     * Builds the UIComponent for the MessageBox of this MessageUIComponent
     *
     * @return UIMessagebox
     */
    private function buildMessageBoxUIComponent(): UIMessagebox
    {
        PLog::debug()->parameter();
        $messageBoxContent = new UILegacyContent($this->buildCloseButtonHTML(), $this->buildTitleParagraphHTML(), $this->buildContentParagraphHTML(), $this->buildFooterUIComponent());
        return PLog::debug()->return(new UIMessagebox(new FailureMessage($messageBoxContent->render())));
    }

    /**
     *
     * @return UILegacyContent
     */
    private function buildUILegacyContent(): UILegacyContent
    {
        PLog::debug()->parameter();
        $examNotificationID = $this->messageID;
        $examNotificationDivID = "ui_uihk_exnot_closeButton_ExamNotificationID_$examNotificationID";
        $content = new UILegacyContent("<div id='$examNotificationDivID'>", $this->buildMessageBoxUIComponent(), '</div>');
        return PLog::debug()->return($content);
    }
}
