<?php
use HSBI\PluginCoreFunctions\Paths;
$errorReportingLevel = error_reporting(E_ERROR);
try {
    require_once __DIR__ . '/../vendor/submodules/plugin-core-functions/autoloader/Autoloader.php';
    require_once Paths::getIliasPath() . '/vendor/composer/vendor/autoload.php';
} catch (Error $e) {
    print "\nThe plugin must be installed before running this test!\n\n'". realpath(__DIR__ . '/../README.md')."' describes how to install this plugin\n\n";
    exit(1);
}
$errorReportingLevel = error_reporting($errorReportingLevel);