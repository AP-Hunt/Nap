<?php
namespace Nap\Application;

use Nap\Events\ResourceMatchingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceMatchMediator implements EventSubscriberInterface
{
    /**
     * @var ActionDispatcher
     */
    private $actionDispatcher;
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    public function __construct(
        ActionDispatcher $actionDispatcher
    ) {
        $this->actionDispatcher = $actionDispatcher;
        $this->request = null;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            ResourceMatchingEvents::RESOURCE_MATCHED => array("resourceMatched")
        );
    }

    public function resourceMatched(\Nap\Events\ResourceMatching\ResourceMatchedEvent $event)
    {
        $this->actionDispatcher->dispatch(
            $this->request,
            $event->getFoundResource()
        );
    }
}