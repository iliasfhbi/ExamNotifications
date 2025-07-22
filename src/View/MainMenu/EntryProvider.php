<?php
namespace HSBI\ExamNotifications\View\MainMenu;

use ILIAS\GlobalScreen\Scope\MainMenu\Provider\AbstractStaticMainMenuPluginProvider;
use HSBI\ExamNotifications\PLog;

/**
 * Provides a main menu entry for the ExamNotifications-plugin.
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * @package ExamNotifications\View\MainMenu
 *         
 */
class EntryProvider extends AbstractStaticMainMenuPluginProvider
{
    use Entry;

    public function getStaticTopItems(): array
    {
        PLog::debug()->parameter();
        return PLog::debug()->return([
            $this->createEntry()
        ]);
    }

    public function getStaticSubItems(): array
    {
        PLog::debug()->parameter();
        return PLog::debug()->return([]);
    }
}