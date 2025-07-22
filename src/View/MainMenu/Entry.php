<?php
namespace HSBI\ExamNotifications\View\MainMenu;

use ILIAS\GlobalScreen\Identification\IdentificationInterface;
use ILIAS\GlobalScreen\Scope\MainMenu\Factory\TopItem\TopLinkItem;
use ExamNotificationsGUI;
use HSBI\ExamNotifications\PLog;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;
use HSBI\ExamNotifications\Control\ExamNotificationsGUI\DefineMessagesCommand;

/**
 * Trait that enables the EntryProvider to create a
 * TopParentItem as main menu entry for the ExamNotifications-plugin.
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * @package ExamNotifications\View\MainMenu
 *        
 */
trait Entry
{
    use AccessManager;
    /**
     * Is used by the GlobalScreen services to identify the main menu entry.
     *
     * @var IdentificationInterface
     */
    private IdentificationInterface $mainMenuEntryId;

    /**
     * Creates a TopLinkItem as main menu entry for the ExamNotifications-plugin.
     *
     * @return TopLinkItem
     */
    private function createEntry(): TopLinkItem
    {
        PLog::debug()->parameter();
        // Gets the link to the ExamNotificationsGUI
        $linkHref = PCFIDIC::CTRL()->getLinkTargetByClass(ExamNotificationsGUI::class, DefineMessagesCommand::getCmd());
        
        $refID = PCFIDIC::CTRL()->getRefID();
        if ($refID !== null)
            //passes the ref_id of the test to ExamNotificationsGUI
            //passes view_control/input_0/input_1=0&view_control/input_0/input_2=25&view_control/input_3/input_4=timestamp&view_control/input_3/input_5=DESC so that the messages are sorted after thier timestamp
            $linkHref .= "&ref_id=$refID&view_control/input_0/input_1=0&view_control/input_0/input_2=25&view_control/input_3/input_4=timestamp&view_control/input_3/input_5=DESC";
        
        $icon = $this->dic->ui()
        ->factory()
        ->symbol()
        ->icon()
        ->standard("adn", "Klausurbenachrichtigung");
        
        // The IdentificationInterface that identifys this main menu entry.
        $this->mainMenuEntryId = $this->id()->identifier("Main_Menu_Entry_ExamNotifications");
                
        // Instance a TopLinkItem that represents the main menu entry for the ExamNotifications-plugin.
        return PLog::debug()->return((new TopLinkItem($this->mainMenuEntryId))
        // The passed method decides if the entry is shown in the main-menu-entries or not.
            ->withVisibilityCallable($this->callableIsAccessAllowed()) 
        // The passed method decides if the entry is available for the main-menu-entries or not.
            ->withAvailableCallable($this->callableIsAccessAllowed())
        // Sets the link that is called when the entry is triggered
            ->withAction($linkHref)
        // Sets the title of the entry
            ->withTitle($this->plugin->txt("pluginName"))
        // Sets the icon of the entry
            ->withSymbol($icon)
        // Sets the entry to the last postition
            ->withPosition(999999));
    }
}