<?php
namespace HSBI\ExamNotifications\View\Message\History;

use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\Columns;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\ReadAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\TupleColumn;
use ilExamNotificationsPlugin;

/**
 * Represents a History
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\View\Message\History
 */
class History extends ReadAction
{
    
    /**
     * Constructs an new instance of History.
     *
     * @param MessageDAO $messageDAO
     * 	The $messageDAO of the new History
     */
    public function __construct(MessageDAO $messageDAO){
        $plugin = ilExamNotificationsPlugin::instance();
        parent::__construct(new Columns(
            new TupleColumn($plugin->txt('timestamp'), $messageDAO->getTimestamp()), 
            new SenderColumn($messageDAO->getSenderUserId()), 
            new TupleColumn($plugin->txt('content'), $messageDAO->getText(), isSortableFlag: false)), 
            $plugin->txt('message_history'));
        $this->messageDAO = $messageDAO->getSenderUserId();
    }
    
}