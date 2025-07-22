<?php
namespace HSBI\ExamNotifications\Model;

/**
 * Defines PluginConfigID
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *
 * @package ExamNotifications\Model
 */
enum PluginConfigID {
        
    case PRESENTATION_TYPE;
    
    case PRESENTATION_TYPE_JS;
    
    case PRESENTATION_TYPE_TOAST;
    
}
