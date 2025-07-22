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
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Delete\DeleteAllAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Export\ExportAllAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Export\ExportType;
use HSBI\PluginCoreFunctions\GUI\Command\Model\UIComponentResult;

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
            'Nachrichten', 
            previousFilterDefinition: $filterDefinition, 
            defaultSortDefinition: $defaultSortDefinition, 
            readAction: new History($messageDAO), 
            createAction: new Creation($messageDAO), 
            deleteAction: new DeleteAllAction('Nachrichtenverlauf löschen'), 
            exportAction: new ExportAllAction(
                'Nachrichtenverlauf als CSV exportieren', 
                exportType: ExportType::CSV, 
                fileName: "Klausurbenachrichtigungsverlauf - $testTitle"
                )
            );

        return PLog::debug()->return(new UIComponentResult(
            $table, 
            "Klausurbenachrichtigung - $testTitle", 
            'templates/default/images/standard/icon_adn.svg')
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

