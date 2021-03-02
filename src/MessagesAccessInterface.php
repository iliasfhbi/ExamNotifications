<?php

namespace ExamNotifications;

interface MessagesAccessInterface
{
    /**
     * @param int $testObjectId
     * @param string $message
     */
    function setMessageForTest(int $testObjectId, string $message);

    /**
     * @param int $testObjectId
     * @return string
     */
    function getMessageForTest(int $testObjectId): string;
}