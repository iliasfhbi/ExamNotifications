<?php
namespace HSBI\ExamNotifications\View\MainMenu;

use HSBI\ExamNotifications\PLog;
use HSBI\PluginCoreFunctions\DependencyInjection\Control\ILIAS\PCFIDIC;

/**
 * Trait that can create a callable that can passed to an instance of
 * ILIAS\GlobalScreen\Scope\MainMenu\Factory\AbstractBaseItem to ensure
 * that the menu entries are only shown to allowed users.
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 * @package ExamNotifications\View\MainMenu
 *         
 */
trait AccessManager
{

    /**
     * Ensures that the menu entries are only shown to allowed users
     *
     * @var callable function (): bool
     */
    private $callableIsAccessAllowed;

    /**
     * Returns a callable that ensures the menu entries are only shown
     * to allowed users
     *
     * @return callable function (): bool
     */
    private function callableIsAccessAllowed(): callable
    {
        PLog::debug()->parameter();
        return PLog::debug()->return($this->callableIsAccessAllowed ?? ($this->callableIsAccessAllowed = PCFIDIC::Objects()->Test()->hasUserWritePermission(...)));
    }
    
}