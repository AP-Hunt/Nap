<?php
namespace Nap\Events;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventedTrait
{
    /** @var EventDispatcherInterface */
    protected $_eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->_eventDispatcher = $dispatcher;
    }

    public function dispatchEvent($eventName, Event $eventData)
    {
        if($this->_eventDispatcher !== null)
        {
            $this->_eventDispatcher->dispatch($eventName, $eventData);
        }
    }
} 