<?php
use HSBI\ExamNotifications\PLog;
use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\ExamNotifications\DAO\MessageViewedDAO;
use HSBI\ExamNotifications\Model\PluginConfigID;
use HSBI\ExamNotifications\View\MainMenu\EntryProvider;
use Pimple\Exception\UnknownIdentifierException;
use HSBI\PluginCoreFunctions\Paths;
use HSBI\PluginCoreFunctions\Config\Plugin\Control\PluginConfigAccess;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use ILIAS\Data\Version;

require_once __DIR__ . '/../submodule/plugin-core-functions/autoloader/Autoloader.php';
require_once __DIR__ . "/../vendor/autoload.php";

include_once ("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");

/**
 * Plugin class
 */
class ilExamNotificationsPlugin extends ilUserInterfaceHookPlugin
{

    public function __construct(ilDBInterface $db, ilComponentRepositoryWrite $component_repository, string $id)
    {
        parent::__construct($db, $component_repository, $id);
        PLog::debug()->parameter();
        if ($this->isActive()) {
            global $DIC;
            try {
                // Registers CourseCreationMainMenuEntryProvider at the GlobalScreen-service.
                // The GlobalScreen-service adds the entry (created by CourseCreationMainMenuEntryProvider)
                // to the main menu.
                $this->getGlobalScreenProviderCollection()->setMainBarProvider((new EntryProvider($DIC, $this)));
            } catch (UnknownIdentifierException $unknownIdentifierException) {
                // Ignore if global_screen is the reason
                if ($unknownIdentifierException->getMessage() != 'Identifier "global_screen" is not defined.')
                    throw $unknownIdentifierException;
            }

            if ($_SERVER['PHP_SELF'] === '/ilias.php') {
                $pluginConfigAccess = new PluginConfigAccess($this->id);
                if ($pluginConfigAccess->getValue(PluginConfigID::PRESENTATION_TYPE, PluginConfigID::PRESENTATION_TYPE_JS->name) === PluginConfigID::PRESENTATION_TYPE_JS->name)
                    // Add/Update version to ensure that the cached Js will be requested again on changes.
                    $DIC->globalScreen()
                        ->layout()
                        ->meta()
                        ->addJs("Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/ExamNotifications/src/ExamNotificationV1.js");
            }
        }
        PLog::debug()->return();
    }

    /**
     *
     * {@inheritdoc}
     * @see ilPlugin::install()
     */
    public function install(): void
    {
        $messageDAO = new MessageDAO();
        $messageDAO->dataSource()->create();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);
        $messageViewedDAO->dataSource()->create();
        parent::install();
    }

    /**
     *
     * {@inheritdoc}
     * @see ilPlugin::uninstall()
     */
    public function uninstall(): bool
    {
        $messageDAO = new MessageDAO();
        $messageViewedDAO = new MessageViewedDAO($messageDAO);
        $messageViewedDAO->dataSource()->delete();
        $messageDAO->dataSource()->delete();
        $pluginConfigAccess = new PluginConfigAccess($this->getId());
        $pluginConfigAccess->deleteAll();
        return parent::uninstall();
    }

    /**
     *
     * {@inheritdoc}
     * @see ilPlugin::update()
     */
    public function update(): bool
    {
        $currentVersion = $this->getPluginInfo()->getCurrentVersion();
        if ($currentVersion === null)
            return parent::update();
        if ($currentVersion->isSmallerThanOrEquals(new Version('1.1.0'))) {
            global $DIC;
            $DIC->database()->dropTable('ui_uihk_exnot_tstmsg', false);
            $messageDAO = new MessageDAO();
            $messageViewedDAO = new MessageViewedDAO($messageDAO);
            $messageDAO->dataSource()->create();
            $messageViewedDAO->dataSource()->create();
            $pluginConfigAccess = new PluginConfigAccess($this->getId());
            $pluginConfigAccess->deleteAll();
        }
        return parent::update();
    }
}