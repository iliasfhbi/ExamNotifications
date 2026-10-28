<?php
namespace HSBI\ExamNotifications\View\Message\UIComponent;

use HSBI\PluginCoreFunctions\UIComponents\UIComponent;
use ILIAS\UI\Component\Component;
use HSBI\ExamNotifications\PLog;

/**
 * Represents a MessageContainerUIComponent
 *
 * @author Felix Kuemmel <felix.kuemmel@hsbi.de>
 *        
 * @package ExamNotifications\View\Message\UIComponent
 */
class MessageContainerUIComponent extends UIComponent
{

    /**
     * The $messageUIComponents of this MessageContainerUIComponent
     *
     * @var array<MessageUIComponent>
     */
    private array $messageUIComponents;

    /**
     * Constructs an new instance of MessageContainerUIComponent.
     *
     * @param array<MessageUIComponent> $messageUIComponents
     *            The $messageUIComponents of the new MessageContainerUIComponent
     */
    public function __construct(MessageUIComponent ...$messageUIComponents)
    {
        parent::__construct();
        $this->messageUIComponents = $messageUIComponents;
    }

    /**
     * Gets the $messageUIComponents of this MessageContainerUIComponent.
     *
     * @return array<MessageUIComponent>
     */
    public function getMessageUIComponents(): array
    {
        return $this->messageUIComponents;
    }

    /**
     * Adds MessageUIComponent(s) to the $messageUIComponents of this MessageContainerUIComponent.
     *
     * @param array<MessageUIComponent> $messageUIComponents
     *            The $messageUIComponents to add to the $messageUIComponents of this MessageContainerUIComponent
     */
    public function addMessageUIComponents(MessageUIComponent ...$messageUIComponents): void
    {
        array_push($this->messageUIComponents, ...$messageUIComponents);
    }

    /**
     * Sets the $messageUIComponents of this MessageContainerUIComponent.
     *
     * @param array<MessageUIComponent> $messageUIComponents
     *            The new $messageUIComponents of this MessageContainerUIComponent
     */
    public function setMessageUIComponents(MessageUIComponent ...$messageUIComponents): void
    {
        $this->messageUIComponents = $messageUIComponents;
    }

    protected function build(): Component
    {
        PLog::debug()->parameter();
        $renderedMessageUIComponents = '';
        foreach ($this->messageUIComponents as /** @var $messageUIComponent MessageUIComponent*/
        $messageUIComponent)
            $renderedMessageUIComponents .= $messageUIComponent->render();
        $messageContainerUIComponent = $this->uiFactory()
            ->legacy()
            ->content($renderedMessageUIComponents);
        return PLog::debug()->return($messageContainerUIComponent);
    }
}
