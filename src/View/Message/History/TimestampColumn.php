<?php
namespace HSBI\ExamNotifications\View\Message\History;

use HSBI\PHPUtilities\CRUD\Model\FieldDefinition;
use HSBI\PHPUtilities\CRUD\Tuple\Model\Tuple;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\Column;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Read\DataType;

/**
 * Represents a TimestampColumn
 * 
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de> 
 * 
 * @package ExamNotifications\View\Message\History
 */
class TimestampColumn extends Column{

	/**
	 * The $timestamp of this TimestampColumn
	 * 
	 * @var FieldDefinition
	 */
	private FieldDefinition $timestamp;

    /**
	 * Constructs an new instance of TimestampColumn.
	 * 
	 * @param FieldDefinition $timestamp
	 * 	The $timestamp of the new TimestampColumn
	 */
	public function __construct(FieldDefinition $timestamp){
	    parent::__construct('Zeitpunkt', DataType::TIMESTAMP);
		$this->timestamp = $timestamp;
	}

	/**
	 * Gets the $timestamp of this TimestampColumn.
	 * 
	 * @return FieldDefinition
	 */
	public function getTimestamp(): FieldDefinition {
		return $this->timestamp;
	}

	/**
	 * Sets the $timestamp of this TimestampColumn.
	 * 
	 * @param FieldDefinition $timestamp
	 * 	The new $timestamp of this TimestampColumn
	 */
	public function setTimestamp(FieldDefinition $timestamp): void {
		$this->timestamp = $timestamp;
	}
	
	/**
	 * {@inheritDoc}
	 * @see Column::getValue()
	 */
	public function getValue(Tuple $tuple): float|int|string
	{
	    return strtotime($tuple->getField($this->timestamp)->getValue());   
	}
	
}
