<?php

namespace ExamNotifications;

use ILIAS\DI\Container;
use ilObjTest;

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

    function setMessageForTest(int $testObjectId, string $message)
    {
        if ($this->testHasDatabaseEntry($testObjectId)) {
            // update message for test
            $this->dic->database()->manipulateF("UPDATE ui_uihk_exnot_tstmsg SET message_text = %s WHERE obj_id = %s;", ["text", "integer"], [$message, $testObjectId]);
        } else {
            // insert message for test
            $this->dic->database()->manipulateF("INSERT INTO ui_uihk_exnot_tstmsg (obj_id, message_text) VALUES (%s, %s);", ["integer", "text"], [$testObjectId, $message]);
        }
    }

    function getMessageForTest(int $testObjectId): string
    {
        if ($this->testHasDatabaseEntry($testObjectId)) {
            $statement = $this->dic->database()->queryF(
                "SELECT message_text FROM ui_uihk_exnot_tstmsg WHERE obj_id = %s;",
                ["integer"],
                [$testObjectId]);

            if ($statement->numRows() > 0) {
                return $statement->fetchAssoc()["message_text"];
            }
        }
        return "";
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