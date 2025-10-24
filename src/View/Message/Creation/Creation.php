<?php
namespace HSBI\ExamNotifications\View\Message\Creation;

use HSBI\ExamNotifications\DAO\MessageDAO;
use HSBI\PHPUtilities\CRUD\Tuple\Model\Tuple;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Create\CreateAction;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Create\InputFields;
use HSBI\PluginCoreFunctions\UIComponents\TableTupleCRUD\Model\Create\InputFieldTextArea;
use HSBI\PluginCoreFunctions\Validation\RegexValidator;
use ilExamNotificationsPlugin;

/**
 * Represents a Creation
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\View\Message\Creation
 */
class Creation extends CreateAction
{
    /**
     * Identifies the content InputField
     * 
     * @var string
     */
    public const CONTENT_INPUT_FIELD_ID = 'content';
    
    /**
     * The $messageDAO of this Creation
     *
     * @var MessageDAO
     */
    private MessageDAO $messageDAO;
    
    /**
     * Constructs an new instance of Creation.
     *
     * @param MessageDAO $messageDAO
     * 	The $messageDAO of the new Creation
     */
    public function __construct(MessageDAO $messageDAO){
        $plugin = ilExamNotificationsPlugin::instance();
        parent::__construct(new InputFields(new InputFieldTextArea(self::CONTENT_INPUT_FIELD_ID, $plugin->txt('content'), new RegexValidator(), 0, 200)), $plugin->txt('new_message'), $plugin->txt('send'));
        $this->messageDAO = $messageDAO;
    }
    public function onCreate(InputFields $inputFields): Tuple
    {
        $tuple = new Tuple($this->messageDAO->getTupleCRUD());
        $tuple->addField($this->messageDAO->getId()->initField(0));
        $tuple->addField($this->messageDAO->getTestReferenceId()->initField($_GET["ref_id"]));
        global $DIC;
        $tuple->addField($this->messageDAO->getSenderUserId()->initField($DIC->user()->getId()));
        $tuple->addField($this->messageDAO->getTimestamp()->initField(date('Y-m-d H:i:s')));
        $tuple->addField($this->messageDAO->getText()->initField($inputFields->getInputFields(self::CONTENT_INPUT_FIELD_ID)->getValue()));
        return $tuple;
    }

    
}