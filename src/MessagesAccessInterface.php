<?php

namespace ExamNotifications;

interface MessagesAccessInterface
{
    /**
     * @param int $testObjectId
     * @param NotificationMessage $message
     */
    function setMessageForTest(int $testObjectId, NotificationMessage $message);

    /**
     * @param int $testObjectId
     * @return NotificationMessage|null
     */
    function getMessageForTest(int $testObjectId);
}