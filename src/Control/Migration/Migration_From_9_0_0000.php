<?php
namespace HSBI\ExamNotifications\Control\Migration;

use HSBI\PHPUtilities\Migration\Model\VersionRange;
use HSBI\PHPUtilities\Migration\Model\Version;
use HSBI\PluginCoreFunctions\Migration\Model\PluginMigration;
use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use ilExamNotificationsPlugin;

/**
 * Mirgration from ExamNotifications 9.0.0000
 * 
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * @package ExamNotifications\Control\Migration
 */
class Migration_From_9_0_0000 extends PluginMigration
{

    /**
     * Constructs an new instance of Migration_From_9_0_0000.
     */
    public function __construct()
    {
        PLog::debug()->parameter();
        parent::__construct(new VersionRange(new Version(9, 0, 0000), new Version(9, 0, 0000)));
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
        $messageDAO = new MessageDAO();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);
        // Copy of Message and MessageViewed
        $messageViewedCopy = $messageViewedDAO->read();
        $messageDAOCopy = $messageDAO->read();
        $messageViewedDAO->dataSource()->delete();
        $messageDAO->dataSource()->delete();
        $messageDAO->dataSource()->create();
        $messageViewedDAO->dataSource()->create();
        $messageDAO->create($messageDAOCopy, null);
        $messageViewedDAO->create($messageViewedCopy, null);
        $plugin = ilExamNotificationsPlugin::instance();
        $plugin->getUiHookPluginInfo()->getPluginConfigAccess()->deleteAll();
        PLog::debug()->return();
    }
}