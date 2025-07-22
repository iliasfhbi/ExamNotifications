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
use HSBI\PHPUtilities\CRUD\Model\Fields;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\FilterOption;
use HSBI\PHPUtilities\CRUD\Model\SortDefinition;
use HSBI\PHPUtilities\CRUD\Model\SortDirection;
use HSBI\PHPUtilities\CRUD\Model\SortOption;
use HSBI\PluginCoreFunctions\Config\Plugin\Control\PluginConfigAccess;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\Objects\Test\TestObject;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Command;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Result;
use HSBI\PluginCoreFunctions\GUI\Command\Model\UIComponentExitResult;
use HSBI\PluginCoreFunctions\GUI\Command\Model\ExitResult;
use Exception;

/**
 * The controller for the CloseMessageCommand of ExamNotificationsGUI
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\Control\ExamNotificationsGUI
 *         
 * @see ExamNotificationsGUI
 */
class CloseMessageCommand implements Command
{

    /**
     *
     * {@inheritdoc}
     * @see Command::run()
     */
    public function run(): Result
    {
        PLog::debug()->parameter();
        $messageId = PCFIDIC::CTRL()->getRefID();
        if ($messageId !== null) {
            $pluginConfigAccess = new PluginConfigAccess();
            $messageDAO = new MessageDAO();
            $messageViewedDAO = new MessageViewedDAO($messageDAO);
            if ($pluginConfigAccess->getValue(PluginConfigID::PRESENTATION_TYPE, PluginConfigID::PRESENTATION_TYPE_JS->name) !== PluginConfigID::PRESENTATION_TYPE_JS->name)
                return PLog::debug()->return(new ExitResult());
            $userID = PCFIDIC::User()->currentUserID();
            try {
                $changes = $messageViewedDAO->update(new Fields($messageViewedDAO->getMessageStatus()
                    ->initField(MessageStatus::CLOSED->value), $messageViewedDAO->getMessageStatusTimestamp()
                    ->initField(date('Y-m-d H:i:s'))), new FilterDefinition(new FilterOption($messageViewedDAO->getMessageId()
                    ->initField($messageId)), new FilterOption($messageViewedDAO->getReceiverUserId()
                    ->initField($userID))));
                if ($changes !== 0)
                    return PLog::debug()->return(new ExitResult());
            } catch (Exception $e) {
                if (sizeof($messageDAO->read(filterDefinition: new FilterDefinition(new FilterOption($messageDAO->getId()
                    ->initField($messageId))))) != 0)
                    throw PLog::debug()->throw($e);
            }
            $message = $messageDAO->read(filterDefinition: new FilterDefinition(new FilterOption($messageDAO->getId()
                ->initField($messageId))))[0] ?? null;
            if ($message !== null)
                $messageViewedDAO->create(new MessageViewed(0, $message->getTestReferenceId(), $message->getId(), $userID, MessageStatus::CLOSED, date('Y-m-d H:i:s')));
        }
        return PLog::debug()->return(new ExitResult());
    }

    /**
     *
     * {@inheritdoc}
     * @see Command::getCmd()
     */
    public static function getCmd(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return('close');
    }
}

