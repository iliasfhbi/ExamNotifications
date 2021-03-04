<?php

namespace ExamNotifications;

use ILIAS\DI\Container;

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
            $this->dic->database()->manipulateF("UPDATE ui_uihk_exnot_tstmsg SET message_text = %s, message_type = %s WHERE obj_id = %s;", ["text", "integer", "integer"], [$message->getText(), $message->getType(), $testObjectId]);
        } else {
            // insert message for test
            $this->dic->database()->manipulateF("INSERT INTO ui_uihk_exnot_tstmsg (obj_id, message_text, message_type) VALUES (%s, %s, %s);", ["integer", "text", "integer"], [$testObjectId, $message->getText(), $message->getType()]);
        }
    }

    function getMessageForTest(int $testObjectId)
    {
        if ($this->testHasDatabaseEntry($testObjectId)) {
            $statement = $this->dic->database()->queryF(
                "SELECT message_text, message_type FROM ui_uihk_exnot_tstmsg WHERE obj_id = %s;",
                ["integer"],
                [$testObjectId]);

            if ($statement->numRows() > 0) {
                $result = $statement->fetchAssoc();
                return new NotificationMessage($result["message_text"], $result["message_type"]);
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