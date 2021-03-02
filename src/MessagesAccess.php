<?php

namespace ExamNotifications;

use ilObjTest;

class MessagesAccess implements MessagesAccessInterface
{

    function setMessageForTest(int $testObjectId, string $message)
    {
        // TODO: save message text to database
    }

    function getMessageForTest(int $testObjectId): string
    {
        // TODO: get message text from database
        $testObject = new ilObjTest($testObjectId, false);
        return "Nachricht für die Prüfung " . $testObject->getTitle();
    }
}