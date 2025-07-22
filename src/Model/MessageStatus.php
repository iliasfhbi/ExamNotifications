<?php
namespace HSBI\ExamNotifications\Model;

use HSBI\PHPUtilities\Enums\Backed\EnumExtension;

/**
 * Defines the MessageStatus
 * 
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de> 
 * 
 * @package ExamNotifications\Model
 */
enum MessageStatus : int {
    use EnumExtension;
    case TOAST = 3;
    case TOASTED = 2;
    case VIEWED = 1;
    case CLOSED = 0;
}
