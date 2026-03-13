<?php
namespace HSBI\ExamNotifications\Control\ExamNotificationsGUI;

use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\View\Message\Creation\Creation;
use HSBI\ExamNotifications\View\Message\History\History;
use HSBI\PHPUtilities\CRUD\Model\ComparisonMode;
use HSBI\PHPUtilities\CRUD\Model\FilterDefinition;
use HSBI\PHPUtilities\CRUD\Model\FilterOption;
use HSBI\PHPUtilities\CRUD\Model\SortDefinition;
use HSBI\PHPUtilities\CRUD\Model\SortDirection;
use HSBI\PHPUtilities\CRUD\Model\SortOption;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Command;
use HSBI\PluginCoreFunctions\GUI\Command\Model\Result;
use HSBI\PluginCoreFunctions\UIComponents\UITableTupleCRUD;
use HSBI\PluginCoreFunctions\UIComponents\Icon\StandardIcon;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Delete\DeleteAllAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Export\ExportAllAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Export\ExportType;
use HSBI\PluginCoreFunctions\GUI\Command\Model\UIComponentResult;
use ilExamNotificationsPlugin;

/**
 * The controller for the DefineMessagesCommand of ExamNotificationsGUI
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\Control\ExamNotificationsGUI
 *         
 * @see ExamNotificationsGUI
 */
class DefineMessagesCommand implements Command
{
    
    /**
     * {@inheritDoc}
     * @see Command::run()
     */
    public function run(): Result
    {
        $messageDAO = new MessageDAO();
        $plugin = ilExamNotificationsPlugin::instance();
        
        $testRefId = PCFIDIC::CTRL()->getRefID();
        if (! PCFIDIC::Objects()->Test()->hasUserWritePermission($testRefId))
            PCFIDIC::Error()->warningRaise('no permission');

        $filterDefinition = new FilterDefinition(
            new FilterOption(
                $messageDAO->getTestReferenceId()->initField($testRefId), 
                ComparisonMode::EQUAL
                )
            );
        
        $defaultSortDefinition = new SortDefinition(
            new SortOption(
                $messageDAO->getTimestamp(), 
                SortDirection::DESCENDING
                )
            );

        $testTitle = PCFIDIC::Objects()->Test()->getTitle($testRefId);

        $table = new UITableTupleCRUD(
            $messageDAO, 
            $plugin->txt('messages'), 
            previousFilterDefinition: $filterDefinition, 
            defaultSortDefinition: $defaultSortDefinition, 
            readAction: new History($messageDAO), 
            createAction: new Creation($messageDAO), 
            deleteAction: new DeleteAllAction($plugin->txt('delete_message_history')), 
            exportAction: new ExportAllAction(
                $plugin->txt('export_message_history_as_CSV'), 
                exportType: ExportType::CSV, 
                fileName: "{$plugin->txt('exam_notification_history')} - $testTitle"
                )
            );

        return PLog::debug()->return(new UIComponentResult(
            $table, 
            "{$plugin->txt('plugin_name')} - $testTitle", 
            StandardIcon::ADMINISTRATIVE_NOTIFICATIONS->getRelativeURL())
            );
    }

    /**
     * {@inheritDoc}
     * @see Command::getCmd()
     */
    public static function getCmd(): string
    {
        PLog::debug()->parameter();
        return PLog::debug()->return('define');
    }
}

