<?php


namespace ExamNotifications;

/**
 * Implementation for accessing and modifying the configuration values
 *
 * @package ExamNotifications
 */
class ConfigurationAccess implements ConfigurationAccessInterface
{
    private $dic;

    public function __construct()
    {
        global $DIC;
        $this->dic = $DIC;
    }


    function getPollingInterval(): int
    {
        $statement = $this->dic->database()->query("SELECT polling_interval FROM ui_uihk_exnot_config;");
        $result = $this->dic->database()->fetchAssoc($statement);
        return $result["polling_interval"];
    }

    function setPollingInterval(int $pollingInterval)
    {
        $this->dic->database()->manipulateF("UPDATE ui_uihk_exnot_config SET polling_interval=%s;", ["integer"], [$pollingInterval]);
    }
}