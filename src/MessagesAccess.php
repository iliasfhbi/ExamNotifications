<?php

namespace ExamNotifications;

use DateTime;
use ILIAS\DI\Container;
use ilObjUser;

class MessagesAccess implements MessagesAccessInterface
{
    /**
     * @var Container
     */
    private $dic;

    public function __construct()
    {
        global $DIC;

        $this->dic = $DIC;
    }

    function setMessageForTest(int $testObjectId, NotificationMessage $message)
    {
        if ($this->testHasDatabaseEntry($testObjectId)) {
            // update message for test
            $this->dic->database()->manipulateF("UPDATE ui_uihk_exnot_tstmsg SET message_text = %s, message_type = %s, message_sender_id = %s, message_timestamp = %s WHERE obj_id = %s;",
                ["text", "integer", "integer", "text", "integer"],
                [$message->getText(), $message->getType(), $message->getSender()->getId(), $message->getTimestamp()->format("Y-m-d H:i:s"), $testObjectId]);
        } else {
            // insert message for test
            $this->dic->database()->manipulateF("INSERT INTO ui_uihk_exnot_tstmsg (obj_id, message_text, message_type, message_sender_id, message_timestamp) VALUES (%s, %s, %s, %s, %s);",
                ["integer", "text", "integer", "integer", "text"],
                [$testObjectId, $message->getText(), $message->getType(), $message->getSender()->getId(), $message->getTimestamp()->format("Y-m-d H:i:s")]);
        }
    }

    function getMessageForTest(int $testObjectId)
    {
        if ($this->testHasDatabaseEntry($testObjectId)) {
            $statement = $this->dic->database()->queryF(
                "SELECT message_text, message_type, message_sender_id, message_timestamp FROM ui_uihk_exnot_tstmsg WHERE obj_id = %s;",
                ["integer"],
                [$testObjectId]);

            if ($statement->numRows() > 0) {
                $result = $statement->fetchAssoc();
                $messageSender = new ilObjUser($result["message_sender_id"]);
                $messageTimestamp = new DateTime($result["message_timestamp"]);
                return new NotificationMessage($result["message_text"], $messageSender, $messageTimestamp, $result["message_type"]);
            }
        }
        return null;
    }

    private function testHasDatabaseEntry(int $testObjectId): bool
    {
        $statement = $this->dic->database()->queryF(
            "SELECT message_text FROM ui_uihk_exnot_tstmsg WHERE obj_id = %s;",
            ["integer"],
            [$testObjectId]);

        return $statement->numRows() > 0;
    }
}