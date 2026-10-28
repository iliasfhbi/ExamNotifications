<?php
namespace HSBI\ExamNotifications\View\Message\UIComponent;

use HSBI\ExamNotifications\Model\Message;
use HSBI\PluginCoreFunctions\UIComponents\UIComponent;
use ILIAS\UI\Component\Component;
use ilExamNotificationsPlugin;
use HSBI\ExamNotifications\PLog;

/**
 * Represents a MessageUIComponent
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\View\Message\UIComponent
 */
class MessageUIComponent extends UIComponent
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
     * 	The $messageID of the new MessageUIComponent
     * @param string $content
     * 	The $content of the new MessageUIComponent
     * @param string $timestamp
     * 	The $timestamp of the new MessageUIComponent
     * @param string $testTitle
     * 	The $testTitle of the new MessageUIComponent
     * @param string|null $testLink
     * 	The $testLink of the new MessageUIComponent
     */
    public function __construct(int $messageID, string $content, string $timestamp, string $testTitle, ?string $testLink){
        parent::__construct();
        $this->messageID = $messageID;
        $this->content = $content;
        $this->timestamp = $timestamp;
        $this->testTitle = $testTitle;
        $this->testLink = $testLink;
    }
    
    /**
     * Gets the $messageID of this MessageUIComponent.
     *
     * @return int
     */
    public function getMessageID(): int {
        return $this->messageID;
    }
    
    /**
     * Sets the $messageID of this MessageUIComponent.
     *
     * @param int $messageID
     * 	The new $messageID of this MessageUIComponent
     */
    public function setMessageID(int $messageID): void {
        $this->messageID = $messageID;
    }
    
    
    /**
     * Gets the $content of this MessageUIComponent.
     *
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }
    
    /**
     * Sets the $content of this MessageUIComponent.
     *
     * @param string $content
     * 	The new $content of this MessageUIComponent
     */
    public function setContent(string $content): void {
        $this->content = $content;
    }
    
    /**
     * Gets the $timestamp of this MessageUIComponent.
     *
     * @return string
     */
    public function getTimestamp(): string {
        return $this->timestamp;
    }
    
    /**
     * Sets the $timestamp of this MessageUIComponent.
     *
     * @param string $timestamp
     * 	The new $timestamp of this MessageUIComponent
     */
    public function setTimestamp(string $timestamp): void {
        $this->timestamp = $timestamp;
    }
    
    /**
     * Gets the $testTitle of this MessageUIComponent.
     *
     * @return string
     */
    public function getTestTitle(): string {
        return $this->testTitle;
    }
    
    /**
     * Sets the $testTitle of this MessageUIComponent.
     *
     * @param string $testTitle
     * 	The new $testTitle of this MessageUIComponent
     */
    public function setTestTitle(string $testTitle): void {
        $this->testTitle = $testTitle;
    }
    
    /**
     * Gets the $testLink of this MessageUIComponent.
     *
     * @return string|null
     */
    public function getTestLink(): ?string {
        return $this->testLink;
    }
    
    /**
     * Sets the $testLink of this MessageUIComponent.
     *
     * @param string|null $testLink
     * 	The new $testLink of this MessageUIComponent
     */
    public function setTestLink(?string $testLink): void {
        $this->testLink = $testLink;
    }
    

    protected function build(): Component
    {
        PLog::debug()->parameter();
        global $DIC;
        $uiFactory = $this->uiFactory();
        $uiRenderer = $DIC->ui()->renderer();
        $plugin = ilExamNotificationsPlugin::instance();

        $examNotificationID = $this->messageID;
        $examNotificationDivID = "ui_uihk_exnot_closeButton_ExamNotificationID_$examNotificationID";
        
        $closeButtonID = "ui_uihk_exnot_closeButton_ExamNotificationID_$examNotificationID";
        $closeMethod = 'ui_uihk_exnot_closeExamNotification'; // See src/ExamNotification.js
        $closeButton = "<button type='button' class='close' aria-label='Schließen' id='$closeButtonID' onclick='$closeMethod(\"$closeButtonID\");'>
        <span aria-hidden='true'>×</span>
        </button>";

        $content = str_replace(["\t", "\n"], ['&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ', '<br/>'], $this->content);
        $messageBoxContent = $closeButton;
        $messageBoxContent .= "<p><b>{$plugin->txt('plugin_name')} - {$this->testTitle}</b></p>";
        $messageBoxContent .= "<p>$content</p>";
        $formattedTimestamp = \DateTime::createFromFormat('Y-m-d H:i:s', $this->timestamp)->format($plugin->txt('date_time_format'));
        $footer = "<hr/><p>{$plugin->txt('sent_at')}: <span>$formattedTimestamp</span>";
        if($this->testLink !== null){
            $testLink = $uiFactory->link()->standard($plugin->txt('view_test_in_new_tab'), $this->testLink)->withOpenInNewViewport(true);
            $footer .= " |&nbsp;{$uiRenderer->render($testLink)}";
        }
        $footer .= "</p>";
        $messageBoxContent .= $footer;

        $messageBox = $uiFactory->messageBox()->failure($messageBoxContent);
        $container = "<div id='$examNotificationDivID'>{$uiRenderer->render($messageBox)}</div>";
        return PLog::debug()->return($uiFactory->legacy()->content($container));
    }
}
