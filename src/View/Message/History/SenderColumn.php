<?php
namespace HSBI\ExamNotifications\View\Message\History;

use HSBI\PHPUtilities\CRUD\Model\FieldDefinition;
use HSBI\PHPUtilities\CRUD\Tuple\Model\Tuple;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\Column;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\DataType;
use ilObjUser;

/**
 * Represents a SenderColumn
 * 
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de> 
 * 
 * @package ExamNotifications\View\Message\History
 */
class SenderColumn extends Column{

	/**
	 * The $senderUserId of this SenderColumn
	 * 
	 * @var FieldDefinition
	 */
	private FieldDefinition $senderUserId;

    /**
	 * Constructs an new instance of SenderColumn.
	 * 
	 * @param FieldDefinition $senderUserId
	 * 	The $senderUserId of the new SenderColumn
	 */
	public function __construct(FieldDefinition $senderUserId){
	    parent::__construct('Sender', DataType::TEXT);
		$this->senderUserId = $senderUserId;
	}

	/**
	 * Gets the $senderUserId of this SenderColumn.
	 * 
	 * @return FieldDefinition
	 */
	public function getSenderUserId(): FieldDefinition {
		return $this->senderUserId;
	}

	/**
	 * Sets the $senderUserId of this SenderColumn.
	 * 
	 * @param FieldDefinition $senderUserId
	 * 	The new $senderUserId of this SenderColumn
	 */
	public function setSenderUserId(FieldDefinition $senderUserId): void {
		$this->senderUserId = $senderUserId;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Column::getValue()
	 */
	public function getValue(Tuple $tuple): float|int|string
	{
	    return ilObjUser::_lookupFullname($tuple->getField($this->senderUserId)->getValue());
	}
	
}
