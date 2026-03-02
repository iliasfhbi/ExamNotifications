<?php
namespace HSBI\ExamNotifications\Control\ExamNotificationsGUI;

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use HSBI\ExamNotifications\Model\Message;
use HSBI\ExamNotifications\Model\MessageStatus;
use HSBI\ExamNotifications\Model\MessageViewed;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\ExamNotifications\View\Message\UIComponent\MessageContainerUIComponent;
use HSBI\ExamNotifications\View\Message\UIComponent\MessageUIComponent;
use HSBI\PHPUtilities\CRUD\Model\ComparisonMode;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\FilterOption;
use HSBI\PHPUtilities\CRUD\Model\SortDefinition;
use HSBI\PHPUtilities\CRUD\Model\SortDirection;
use HSBI\PHPUtilities\CRUD\Model\SortOption;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\Objects\Test\TestObject;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Command;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Result;
use HSBI\PluginCoreFunctions\GUI\Command\Model\UIComponentExitResult;
use HSBI\PluginCoreFunctions\GUI\Command\Model\ExitResult;
use ilExamNotificationsPlugin;

/**
 * The controller for the GetMessagesCommand of ExamNotificationsGUI
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\Control\ExamNotificationsGUI
 *         
 * @see ExamNotificationsGUI
 */
class GetMessagesCommand implements Command
{

    /**
     *
     * {@inheritdoc}
     * @see Command::run()
     */
    public function run(): Result
    {
        PLog::debug()->parameter();

        $pluginConfigAccess = ilExamNotificationsPlugin::instance()->getUiHookPluginInfo()->getPluginConfigAccess();
        $messageDAO = new MessageDAO();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);

        if ($pluginConfigAccess->getValue(PluginConfigID::PRESENTATION_TYPE, PluginConfigID::PRESENTATION_TYPE_JS->name) !== PluginConfigID::PRESENTATION_TYPE_JS->name)
            return PLog::debug()->return(new ExitResult());

        $closedMessageFilterDefinition = [];

        
        $userID = PCFIDIC::User()->currentUserID();
        $userHasActiveTests = false;
        $userTests = [];
        $userTestsRefs = [];

        foreach (PCFIDIC::Objects()->Test()->getAllTestObjectsOfUser($userID) as $refId => /** @var $testObject TestObject*/
        $testObject) {
            if ($testObject->hasUnfinishedPasses($userID)) {
                $userHasActiveTests = true;
                $userTests[$refId] = $testObject;
            }
        }
        $userTestsRefs = array_keys($userTests);

        if (! $userHasActiveTests)
            return PLog::debug()->return(new ExitResult());

        $messageStates = [];
        $receiverFilterOption = new FilterOption($messageViewedDAO->getReceiverUserId()->initField($userID));
        foreach ($userTestsRefs as $refId) {
            $closedMessageFilterDefinition[$refId] = new FilterDefinition();
            $messageViewedFilterDefinition = new FilterDefinition();
            $messageViewedFilterDefinition->addFilterOption(new FilterOption($messageViewedDAO->getTestReferenceId()
                ->initField($refId)));
            $messageViewedFilterDefinition->addFilterOption($receiverFilterOption);
            foreach ($messageViewedDAO->read(filterDefinition: $messageViewedFilterDefinition) as /** @var $messageViewed MessageViewed */
            $messageViewed) {
                $messageStatus = $messageViewed->getMessageStatus();
                $messageStates[$messageViewed->getMessageId()] = $messageStatus;
                if ($messageStatus === MessageStatus::CLOSED)
                    $closedMessageFilterDefinition[$refId]->addFilterOption(new FilterOption($messageDAO->getId()
                        ->initField($messageViewed->getMessageId()), comparisonMode: ComparisonMode::NOT_EQUAL));
            }
        }

        $messages = [];

        foreach ($userTestsRefs as $refId) {
            $messageFilterDefinition = $closedMessageFilterDefinition[$refId];
            $messageFilterDefinition->addFilterOption(new FilterOption($messageDAO->getTestReferenceId()
                ->initField($refId)));
            $testMessages = $messageDAO->read(filterDefinition: $messageFilterDefinition);
            array_push($messages, ...$testMessages);
        }
        
        usort($messages, function(Message $message1, Message $message2) :int {
            return strcmp($message1->getTimestamp(), $message2->getTimestamp());
        });

        if (sizeof($messages) == 0)
            return PLog::debug()->return(new ExitResult());

        $cmdClass = PCFIDIC::CTRL()->getCmdClass();

        $isCmdClassTestPlayer = $cmdClass === 'iltestplayerrandomquestionsetgui' || $cmdClass === 'iltestplayerfixedquestionsetgui' || $cmdClass === 'iltestscreengui';
        $thisRef = null;
        if ($isCmdClassTestPlayer)
            $thisRef = PCFIDIC::CTRL()->getRefID();
        else {
            $refCmdClass = PCFIDIC::CTRL()->HTTPReferer()->getCmdClass();
            $isCmdClassTestPlayer = $refCmdClass === 'iltestplayerrandomquestionsetgui' || $refCmdClass === 'iltestplayerfixedquestionsetgui' || $refCmdClass === 'iltestscreengui';
            $thisRef = PCFIDIC::CTRL()->HTTPReferer()->getRefID();
        }

        $messageContainerUIComponent = new MessageContainerUIComponent();
        foreach ($messages as /** @var $message Message*/
        $message) {
            $messageStatus = $messageStates[$message->getId()] ?? null;
            $testRef = $message->getTestReferenceId();
            $title = $userTests[$message->getTestReferenceId()]->getTitle();
            $link = null;
            if (! ($isCmdClassTestPlayer && $thisRef === $testRef))
                $link = "ilias.php?baseClass=ilrepositorygui&cmdClass=ilTestScreenGUI&cmd=testScreen&ref_id=$testRef";
            $messageContainerUIComponent->addMessageUIComponents(new MessageUIComponent($message->getId(), $message->getText(), $message->getTimestamp(), $title, $link));
        }
        return PLog::debug()->return(new UIComponentExitResult($messageContainerUIComponent));
    }

    /**
     *
     * {@inheritdoc}
     * @see Command::getCmd()
     */
    public static function getCmd(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return('get');
    }
}

