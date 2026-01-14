<?php
namespace HSBI\ExamNotifications;
require_once __DIR__ . '/../vendor/submodules/plugin-core-functions/autoloader/Autoloader.php';

use HSBI\PHPUtilities\Logger\LogInterface;
use HSBI\PluginCoreFunctions\Logger\PluginLog;

/**
 * Enables to log Plugin actions.
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * @package ExamNotifications
 *
 */
class PLog implements LogInterface
{
    use PluginLog;
}

PLog::setConfig([
    "defaultLogLevel" => "ERROR",
    "logDetailed" => "0",
    "pathContext" => null
]);