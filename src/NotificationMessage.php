<?php

namespace ExamNotifications;

class NotificationMessage
{
    const MAXIMUM_LENGTH = 200;

    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $type;

    /**
     * NotificationMessage constructor.
     * @param string $text
     * @param int $type
     */
    public function __construct(string $text, int $type = MessageTypes::DANGER)
    {
        $this->text = $text;
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
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }
}