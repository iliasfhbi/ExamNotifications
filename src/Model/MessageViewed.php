<?php
namespace HSBI\ExamNotifications\Model;

/**
 * Represents a MessageViewed
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\Model
 */
class MessageViewed
{

    /**
     * The $id of this MessageViewed
     * 
     * @var int
     */
    private int $id;

    /**
     * The $testReferenceId of this MessageViewed
     *
     * @var int
     */
    private int $testReferenceId;

    /**
     * The $messageId of this MessageViewed
     *
     * @var int
     */
    private int $messageId;

    /**
     * The $receiverUserId of this MessageViewed
     *
     * @var int
     */
    private int $receiverUserId;

    /**
     * The $messageStatus of this MessageViewed
     *
     * @var MessageStatus
     */
    private MessageStatus $messageStatus;
    
    /**
     * The $messageStatusTimestamp of this Message
     *
     * @var string
     */
    private string $messageStatusTimestamp;

    /**
     * Constructs an new instance of MessageViewed.
     *
     * @param int $id
     *            The $id of the new MessageViewed
     * @param int $testReferenceId
     *            The $testReferenceId of the new MessageViewed
     * @param int $messageId
     *            The $messageId of the new MessageViewed
     * @param int $receiverUserId
     *            The $receiverUserId of the new MessageViewed
     * @param MessageStatus $messageStatus
     *            The $messageStatus of the new MessageViewed
     * @param string $messageStatusTimestamp
     *            The $messageStatusTimestamp of the new MessageViewed
     */
    public function __construct(int $id, int $testReferenceId, int $messageId, int $receiverUserId, MessageStatus $messageStatus, string $messageStatusTimestamp)
    {
        $this->id = $id;
        $this->testReferenceId = $testReferenceId;
        $this->messageId = $messageId;
        $this->receiverUserId = $receiverUserId;
        $this->messageStatus = $messageStatus;
        $this->messageStatusTimestamp = $messageStatusTimestamp;
    }

    /**
     * Gets the $id of this MessageViewed.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the $id of this MessageViewed.
     *
     * @param int $id
     *            The new $id of this MessageViewed
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the $testReferenceId of this MessageViewed.
     *
     * @return int
     */
    public function getTestReferenceId(): int
    {
        return $this->testReferenceId;
    }

    /**
     * Sets the $testReferenceId of this MessageViewed.
     *
     * @param int $testReferenceId
     *            The new $testReferenceId of this Message
     */
    public function setTestReferenceId(int $testReferenceId): void
    {
        $this->testReferenceId = $testReferenceId;
    }

    /**
     * Gets the $messageId of this MessageViewed.
     *
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * Sets the $messageId of this MessageViewed.
     *
     * @param int $messageId
     *            The new $messageId of this MessageViewed
     */
    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * Gets the $receiverUserId of this MessageViewed.
     *
     * @return int
     */
    public function getReceiverUserId(): int
    {
        return $this->receiverUserId;
    }

    /**
     * Sets the $receiverUserId of this MessageViewed.
     *
     * @param int $receiverUserId
     *            The new $receiverUserId of this MessageViewed
     */
    public function setReceiverUserId(int $receiverUserId): void
    {
        $this->receiverUserId = $receiverUserId;
    }

    /**
     * Gets the $messageStatus of this MessageViewed.
     *
     * @return MessageStatus
     */
    public function getMessageStatus(): MessageStatus
    {
        return $this->messageStatus;
    }

    /**
     * Sets the $messageStatus of this MessageViewed.
     *
     * @param MessageStatus $messageStatus
     *            The new $messageStatus of this MessageViewed
     */
    public function setMessageStatus(MessageStatus $messageStatus): void
    {
        $this->messageStatus = $messageStatus;
    }
    
    /**
     * Gets the $messageStatusTimestamp of this MessageViewed.
     *
     * @return string
     */
    public function getMessageStatusTimestamp(): string {
        return $this->messageStatusTimestamp;
    }
    
    /**
     * Sets the $messageStatusTimestamp of this MessageViewed.
     *
     * @param string $messageStatusTimestamp
     * 	The new $messageStatusTimestamp of this MessageViewed
     */
    public function setMessageStatusTimestamp(string $messageStatusTimestamp): void {
        $this->messageStatusTimestamp = $messageStatusTimestamp;
    }
}
