<?php
namespace HSBI\ExamNotifications\Control\Migration;

use HSBI\PHPUtilities\Migration\Model\VersionRange;
use HSBI\PHPUtilities\Migration\Model\Version;
use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use ilExamNotificationsPlugin;
use HSBI\PluginCoreFunctions\CRUD\DataSource;
use HSBI\PluginCoreFunctions\Migration\Model\PluginMigration;
use HSBI\PHPUtilities\CRUD\Model\RecordDefinition;

/**
 * Mirgration from ExamNotifications 1.1.0
 * 
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * @package HSBI\ExamNotifications\Control\Migration
 */
class Migration_From_1_1_0 extends PluginMigration
{

    /**
     * Constructs an new instance of Migration_From_1_1_0.
     */
    public function __construct()
    {
        PLog::debug()->parameter();
        parent::__construct(new VersionRange(new Version(1, 1, 0), new Version(1, 1, 0)));
        PLog::debug()->return();
    }

    /**
     *
     * {@inheritdoc}
     * @see \HSBI\PHPUtilities\Migration\Model\Migration::migrate()
     */
    protected function migrate(): void
    {
        PLog::debug()->parameter();
//         global $DIC;
//         $DIC->database()->dropTable('ui_uihk_exnot_tstmsg', false);
        $tstmsgDataSource = new DataSource(new RecordDefinition(), 'ui_uihk_exnot_tstmsg');
        $tstmsgDataSource->delete();
        $messageDAO = new MessageDAO();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);
        $messageDAO->dataSource()->create();
        $messageViewedDAO->dataSource()->create();
        $plugin = ilExamNotificationsPlugin::instance();
        $plugin->getUiHookPluginInfo()->getPluginConfigAccess()->deleteAll();
        PLog::debug()->return();
    }
}

