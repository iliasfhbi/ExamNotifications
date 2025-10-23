<?php
namespace HSBI\ExamNotifications\DAO;

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Model\Message;
use HSBI\PHPUtilities\CRUD\Model\ComparisonMode;
use HSBI\PHPUtilities\CRUD\Model\Field;
use HSBI\PHPUtilities\CRUD\Model\FieldDefinition;
use HSBI\PHPUtilities\CRUD\Model\FieldIdentifier;
use HSBI\PHPUtilities\CRUD\Model\FieldSelection;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\RecordDefinition;
use HSBI\PHPUtilities\CRUD\Model\SearchDefinition;
use HSBI\PHPUtilities\CRUD\Tuple\Control\TupleBasedObjectCRUDController;
use HSBI\PHPUtilities\CRUD\Tuple\Model\Tuple;
use HSBI\PluginCoreFunctions\CRUD\DataSource;
use HSBI\PluginCoreFunctions\CRUD\TupleCRUD;

/**
 * Implementation for accessing and modifying the NotificationMessage(s) for a test
 *
 * @link ui_uihk_exnot_message
 *      
 * @package ExamNotifications\DAO
 */
class MessageDAO extends TupleBasedObjectCRUDController
{

    /**
     * The $id FieldDefinition of this MessageDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $id;

    /**
     * The $testReferenceId FieldDefinition of this MessageDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $testReferenceId;

    /**
     * The $senderUserId FieldDefinition of this MessageDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $senderUserId;

    /**
     * The $timestamp FieldDefinition of this MessageDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $timestamp;

    /**
     * The $text FieldDefinition of this MessageDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $text;

    /**
     *
     * {@inheritdoc}
     * @see \HSBI\PHPUtilities\CRUD\Tuple\Control\TupleBasedObjectCRUDController::initTupleCRUD()
     */
    protected function initTupleCRUD(): TupleCRUD
    {
        PLog::debug()->parameter();
        $dataSourceId = 'ui_uihk_exnot_message';
        $recordDefinition = new RecordDefinition();
        $this->id = new FieldDefinition('id', $dataSourceId, 'integer', isPrimaryKeyFlag: true);
        $this->testReferenceId = new FieldDefinition('test_reference_id', $dataSourceId, 'integer', referencedField: new FieldIdentifier('ref_id', 'object_reference'));
        $this->senderUserId = new FieldDefinition('sender_user_id', $dataSourceId, 'integer', referencedField: new FieldIdentifier('usr_id', 'usr_data'));
        $this->timestamp = new FieldDefinition('timestamp', $dataSourceId, 'timestamp');
        $this->text = new FieldDefinition('text', $dataSourceId, 'text', length: 200);
        $recordDefinition->setFieldDefinitions($this->id, $this->testReferenceId, $this->senderUserId, $this->timestamp, $this->text);
        $dataSource = new DataSource($recordDefinition, $dataSourceId);
        $tupleCRUD = new TupleCRUD($dataSource);
        return PLog::debug()->return($tupleCRUD);
    }

    /**
     *
     * {@inheritdoc}
     * @see \HSBI\PHPUtilities\CRUD\Control\ObjectCRUDController::_toObject()
     */
    protected function _toObject(Tuple $tuple): object
    {
        PLog::debug()->parameter();
        return PLog::debug()->return(new Message($tuple->getField($this->id)
            ->getValue(), $tuple->getField($this->testReferenceId)
            ->getValue(), $tuple->getField($this->senderUserId)
            ->getValue(), $tuple->getField($this->timestamp)
            ->getValue(), $tuple->getField($this->text)
            ->getValue()));
    }

    /**
     *
     * {@inheritdoc}
     * @see \HSBI\PHPUtilities\CRUD\Control\ObjectCRUDController::_toTuple()
     */
    protected function _toTuple(object $object): Tuple
    {
        PLog::debug()->parameter();
        /**
         *
         * @var $object Message
         */
        return PLog::debug()->return(new Tuple($this->getTupleCRUD(), $this->id->initField($object->getId()), $this->testReferenceId->initField($object->getTestReferenceId()), $this->senderUserId->initField($object->getSenderUserId()), $this->timestamp->initField($object->getTimestamp()), $this->text->initField($object->getText())));
    }

    /**
     * Gets the $id FieldDefinition of this MessageDAO.
     *
     * @return FieldDefinition
     */
    public function getId(): FieldDefinition
    {
        return $this->id;
    }

    /**
     * Gets the $testReferenceId FieldDefinition of this MessageDAO.
     *
     * @return FieldDefinition
     */
    public function getTestReferenceId(): FieldDefinition
    {
        return $this->testReferenceId;
    }

    /**
     * Gets the $senderUserId FieldDefinition of this MessageDAO.
     *
     * @return FieldDefinition
     */
    public function getSenderUserId(): FieldDefinition
    {
        return $this->senderUserId;
    }

    /**
     * Gets the $timestamp FieldDefinition of this MessageDAO.
     *
     * @return FieldDefinition
     */
    public function getTimestamp(): FieldDefinition
    {
        return $this->timestamp;
    }

    /**
     * Gets the $text FieldDefinition of this MessageDAO.
     *
     * @return FieldDefinition
     */
    public function getText(): FieldDefinition
    {
        return $this->text;
    }
}