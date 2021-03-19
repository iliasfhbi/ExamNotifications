<?php


namespace ExamNotifications;

/**
 * Operations for accessing and modifying the configuration values
 *
 * @package ExamNotifications
 */
interface ConfigurationAccessInterface
{
    /**
     * @return int Polling interval in seconds
     */
    function getPollingInterval(): int;

    /**
     * @param int $pollingInterval Polling interval in seconds
     * @return void
     */
    function setPollingInterval(int $pollingInterval);
}