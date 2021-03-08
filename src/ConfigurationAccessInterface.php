<?php


namespace ExamNotifications;


interface ConfigurationAccessInterface
{
    /**
     * @return int Polling interval in seconds
     */
    function getPollingInterval(): int;

    /**
     * @param int $pollingInterval Polling interval in seconds
     */
    function setPollingInterval(int $pollingInterval);
}