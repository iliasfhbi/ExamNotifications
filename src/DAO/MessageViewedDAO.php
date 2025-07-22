<?php
namespace HSBI\ExamNotifications\DAO;

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\Model\MessageViewed;
use HSBI\PHPUtilities\CRUD\Model\FieldDefinition;
use HSBI\PHPUtilities\CRUD\Model\FieldIdentifier;
use HSBI\PHPUtilities\CRUD\Model\RecordDefinition;
use HSBI\PHPUtilities\CRUD\Tuple\Control\TupleBasedObjectCRUDController;
use HSBI\PHPUtilities\CRUD\Tuple\Model\Tuple;
use HSBI\PluginCoreFunctions\CRUD\DataSource;
use HSBI\PluginCoreFunctions\CRUD\TupleCRUD;
use HSBI\ExamNotifications\Model\MessageStatus;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\FilterOption;

/**
 * Implementation for accessing and modifying the NotificationMessageViewed(s) for a test
 *
 * @link ui_uihk_exnot_viewed
 *      
 * @package ExamNotifications\DAO
 */
class MessageViewedDAO extends TupleBasedObjectCRUDController
{

    /**
     * The $id FieldDefinition of this MessageViewedDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $id;

    /**
     * The $testReferenceId FieldDefinition of this MessageViewedDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $testReferenceId;

    /**
     * The $messageId FieldDefinition of this MessageViewedDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $messageId;

    /**
     * The $receiverUserId FieldDefinition of this MessageViewedDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $receiverUserId;

    /**
     * The $messageStatus FieldDefinition of this MessageViewedDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $messageStatus;
    
    /**
     * The $messageStatusTimestamp FieldDefinition of this MessageDAO
     *
     * @var FieldDefinition
     */
    private FieldDefinition $messageStatusTimestamp;

    /**
     * The $messageDAO of this MessageViewedDAO
     *
     * @var MessageDAO
     */
    private MessageDAO $messageDAO;

    /**
     * Constructs an new instance of MessageViewedDAO.
     *
     * @param MessageDAO $messageDAO
     *            (optional by default a new MessageDAO will be instantiated)
     */
    public function __construct(?MessageDAO $messageDAO = null)
    {
        $this->messageDAO = $messageDAO === null ? new MessageDAO() : $messageDAO;
        parent::__construct();
    }

    /**
     *
     * {@inheritdoc}
     * @see \HSBI\PHPUtilities\CRUD\Tuple\Control\TupleBasedObjectCRUDController::initTupleCRUD()
     */
    protected function initTupleCRUD(): TupleCRUD
    {
        PLog::debug()->parameter();
        $dataSourceId = 'ui_uihk_exnot_viewed';
        $recordDefinition = new RecordDefinition();
        $this->id = new FieldDefinition('id', $dataSourceId, 'integer', isPrimaryKeyFlag: true);
        $this->testReferenceId = new FieldDefinition('test_reference_id', $dataSourceId, 'integer', referencedField: new FieldIdentifier('ref_id', 'object_reference'));
        //$this->messageId = new FieldDefinition('message_id', $dataSourceId, 'integer', referencedField: new FieldIdentifier($this->messageDAO->getId()->getName(), $this->messageDAO->dataSource()->id()));
        /*
         * Because of an missing use definition in ilDBPdoManager::addForeignKey() CASCADE is not available!
         * So that this anonymous class enables to delete cascading. Remove this with the line above if the bug is fixed.
         */
        $this->messageId = new FieldDefinition('message_id', $dataSourceId, 'integer', referencedField: new FieldIdentifier($this->messageDAO->getId()->getName(), 'ui_uihk_exnot_message'));
        $this->receiverUserId = new FieldDefinition('receiver_user_id', $dataSourceId, 'integer', referencedField: new FieldIdentifier('usr_id', 'usr_data'));
        $this->messageStatus = new FieldDefinition('message_status', $dataSourceId, 'integer');
        $this->messageStatusTimestamp = new FieldDefinition('message_status_timestamp', $dataSourceId, 'timestamp');
        $recordDefinition->setFieldDefinitions($this->id, $this->testReferenceId, $this->messageId, $this->receiverUserId, $this->messageStatus, $this->messageStatusTimestamp);
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
        $messageStatus = MessageStatus::getByCaseValue($tuple->getField($this->messageStatus)
            ->getValue(), returnOnlyFirstMatch: true);

        return PLog::debug()->return(new MessageViewed($tuple->getField($this->id)
            ->getValue(), $tuple->getField($this->testReferenceId)
            ->getValue(), $tuple->getField($this->messageId)
            ->getValue(), $tuple->getField($this->receiverUserId)
            ->getValue(), $messageStatus, $tuple->getField($this->messageStatusTimestamp)
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
         * @var $object MessageViewed
         */
        return PLog::debug()->return(new Tuple($this->getTupleCRUD(), 
            $this->id->initField($object->getId()), 
            $this->testReferenceId->initField($object->getTestReferenceId()), 
            $this->messageId->initField($object->getMessageId()), 
            $this->receiverUserId->initField($object->getReceiverUserId()), 
            $this->messageStatus->initField($object->getMessageStatus()->value), 
            $this->messageStatusTimestamp->initField($object->getMessageStatusTimestamp())));
    }

    /**
     * Gets the $id FieldDefinition of this MessageViewedDAO.
     *
     * @return FieldDefinition
     */
    public function getId(): FieldDefinition
    {
        return $this->id;
    }

    /**
     * Gets the $testReferenceId FieldDefinition of this MessageViewedDAO.
     *
     * @return FieldDefinition
     */
    public function getTestReferenceId(): FieldDefinition
    {
        return $this->testReferenceId;
    }

    /**
     * Gets the $messageId FieldDefinition of this MessageViewedDAO.
     *
     * @return FieldDefinition
     */
    public function getMessageId(): FieldDefinition
    {
        return $this->messageId;
    }

    /**
     * Gets the $receiverUserId FieldDefinition of this MessageViewedDAO.
     *
     * @return FieldDefinition
     */
    public function getReceiverUserId(): FieldDefinition
    {
        return $this->receiverUserId;
    }

    /**
     * Gets the $messageStatus FieldDefinition of this MessageViewedDAO.
     *
     * @return FieldDefinition
     */
    public function getMessageStatus(): FieldDefinition
    {
        return $this->messageStatus;
    }
    
    /**
     * Gets the $messageStatusTimestamp FieldDefinition of this MessageDAO.
     *
     * @return FieldDefinition
     */
    public function getMessageStatusTimestamp(): FieldDefinition
    {
        return $this->messageStatusTimestamp;
    }
}