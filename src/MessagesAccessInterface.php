<?php

namespace ExamNotifications;

/**
 * Operations for accessing and modifying the message set for a test
 *
 * @package ExamNotifications
 */
interface MessagesAccessInterface
{
    /**
     * Sets the current message for a test object
     *
     * @param int $testObjectId
     * @param NotificationMessage $message
     * @return void
     */
    function setMessageForTest(int $testObjectId, NotificationMessage $message);

    /**
     * Retrieves the message that is currently set for the specified test object
     *
     * @param int $testObjectId
     * @return NotificationMessage|null
     */
    function getMessageForTest(int $testObjectId);
}