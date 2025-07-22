<?php
namespace HSBI\ExamNotifications\Model;

/**
 * Represents a Message
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *
 * @package ExamNotifications\Model
 */
class Message{
    
    /**
     * The $id of this Message
     *
     * @var int
     */
    private int $id;
    
    /**
     * The $testReferenceId of this Message
     *
     * @var int
     */
    private int $testReferenceId;
    
    /**
     * The $senderUserId of this Message
     *
     * @var int
     */
    private int $senderUserId;
    
    /**
     * The $timestamp of this Message
     *
     * @var string
     */
    private string $timestamp;
    
    /**
     * The $text of this Message
     *
     * @var string
     */
    private string $text;
    
    /**
     * Constructs an new instance of Message.
     *
     * @param int $id
     * 	The $id of the new Message
     * @param int $testReferenceId
     * 	The $testReferenceId of the new Message
     * @param int $senderUserId
     * 	The $senderUserId of the new Message
     * @param string $timestamp
     * 	The $timestamp of the new Message
     * @param string $text
     * 	The $text of the new Message
     */
    public function __construct(int $id, int $testReferenceId, int $senderUserId, string $timestamp, string $text){
        $this->id = $id;
        $this->testReferenceId = $testReferenceId;
        $this->senderUserId = $senderUserId;
        $this->timestamp = $timestamp;
        $this->text = $text;
    }
    
    /**
     * Gets the $id of this Message.
     *
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
    
    /**
     * Sets the $id of this Message.
     *
     * @param int $id
     * 	The new $id of this Message
     */
    public function setId(int $id): void {
        $this->id = $id;
    }
    
    /**
     * Gets the $testReferenceId of this Message.
     *
     * @return int
     */
    public function getTestReferenceId(): int {
        return $this->testReferenceId;
    }
    
    /**
     * Sets the $testReferenceId of this Message.
     *
     * @param int $testReferenceId
     * 	The new $testReferenceId of this Message
     */
    public function setTestReferenceId(int $testReferenceId): void {
        $this->testReferenceId = $testReferenceId;
    }
    
    /**
     * Gets the $senderUserId of this Message.
     *
     * @return int
     */
    public function getSenderUserId(): int {
        return $this->senderUserId;
    }
    
    /**
     * Sets the $senderUserId of this Message.
     *
     * @param int $senderUserId
     * 	The new $senderUserId of this Message
     */
    public function setSenderUserId(int $senderUserId): void {
        $this->senderUserId = $senderUserId;
    }
    
    /**
     * Gets the $timestamp of this Message.
     *
     * @return string
     */
    public function getTimestamp(): string {
        return $this->timestamp;
    }
    
    /**
     * Sets the $timestamp of this Message.
     *
     * @param string $timestamp
     * 	The new $timestamp of this Message
     */
    public function setTimestamp(string $timestamp): void {
        $this->timestamp = $timestamp;
    }
    
    /**
     * Gets the $text of this Message.
     *
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }
    
    /**
     * Sets the $text of this Message.
     *
     * @param string $text
     * 	The new $text of this Message
     */
    public function setText(string $text): void {
        $this->text = $text;
    }
    
}
