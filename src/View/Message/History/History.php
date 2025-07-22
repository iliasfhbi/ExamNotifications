<?php
namespace HSBI\ExamNotifications\View\Message\History;

use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\Columns;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\ReadAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\TupleColumn;

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
        parent::__construct(new Columns(
            new TupleColumn('Zeitpunkt', $messageDAO->getTimestamp()), 
            new SenderColumn($messageDAO->getSenderUserId()), 
            new TupleColumn('Inhalt', $messageDAO->getText(), isSortableFlag: false)), 
            'Nachrichtenverlauf');
        $this->messageDAO = $messageDAO->getSenderUserId();
    }
    
}