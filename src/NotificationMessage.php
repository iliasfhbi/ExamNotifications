<?php

namespace ExamNotifications;

use DateTime;
use ilObjUser;

/**
 * Notification message
 * @package ExamNotifications
 */
class NotificationMessage
{
    /**
     * Maximum length of message text in characters
     * @var int
     */
    const MAXIMUM_LENGTH = 200;

    /**
     * @var string
     */
    private $text;
    /**
     * @var ilObjUser
     */
    private $sender;
    /**
     * @var DateTime
     */
    private $timestamp;

    /**
     * Message type
     * @link MessageTypes
     * @var int
     */
    private $type;

    /**
     * NotificationMessage constructor.
     * @param string $text
     * @param ilObjUser $sender
     * @param DateTime $timestamp
     * @param int $type
     */
    public function __construct(string $text, ilObjUser $sender, DateTime $timestamp, int $type = MessageTypes::DANGER)
    {
        $this->text = $text;
        $this->sender = $sender;
        $this->timestamp = $timestamp;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return void
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return ilObjUser|null
     */
    public function getSender(): ilObjUser
    {
        return $this->sender;
    }

    /**
     * @param ilObjUser $sender
     * @return void
     */
    public function setSender(ilObjUser $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return DateTime|null
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     * @return void
     */
    public function setTimestamp(DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int message type
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type message type
     * @return void
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }
}