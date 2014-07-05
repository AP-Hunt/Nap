<?php
namespace Nap\Application;

use Nap\Events\ActionDispatcherEvents;
use Nap\Events\ResourceMatchingEvents;
use Nap\Response\HeaderResultsInterface;
use Nap\Response\Responder;
use Nap\Response\Result\HTTP\NotAcceptable;
use Nap\Response\Result\HTTP\NotFound;
use Nap\Response\Result\HTTP\OK;
use Nap\Serialisation\SerialiserRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResponseMediator implements EventSubscriberInterface
{
    /** @var \Nap\Response\Responder */
    private $responder;

    /** @var \Nap\Serialisation\SerialiserRegistry */
    private $serialiserRegistry;

    public function __construct(
        Responder $responder,
        SerialiserRegistry $serialiserRegistry
    ) {
        $this->responder = $responder;
        $this->serialiserRegistry = $serialiserRegistry;
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
            ResourceMatchingEvents::RESOURCE_NOT_MATCHED => array("notFound", 999),
            ActionDispatcherEvents::NO_APPROPRIATE_RESPONSE => array("noAppropriateResponse", 999),
            ActionDispatcherEvents::CONTROLLER_EXECUTED => array("validResponse", 999)
        );
    }

    public function notFound(\Nap\Events\ResourceMatching\ResourceNotMatchedEvent $notMatchedEvent)
    {
        $headers = new NotFound($notMatchedEvent->getResult());
        $this->sendResponse($headers, "");
    }

    public function noAppropriateResponse(\Nap\Events\ActionDispatcher\NoAppropriateResponseEvent $noAppropriateResponseEvent)
    {
        $headers = new NotAcceptable($noAppropriateResponseEvent->getResult());
        $this->sendResponse($headers, "");
    }

    public function validResponse(\Nap\Events\ActionDispatcher\ControllerExecutedEvent $controllerExecutedEvent)
    {
        $serialiser = $this->serialiserRegistry->getSerialiser($controllerExecutedEvent->getMimeType());
        if($serialiser === null)
        {
            throw new \Nap\Application\NoSerialiserConfiguredException(
                sprintf("No serialiser configured for mime type: %s", $controllerExecutedEvent->getMimeType())
            );
        }

        $headers = new OK($controllerExecutedEvent->getResult());

        $this->sendResponse(
            $headers,
            $serialiser->serialise($controllerExecutedEvent->getResult()->getData())
        );
    }

    private function sendResponse(HeaderResultsInterface $headers, $bodyContent)
    {
        $this->responder->writeResponse($headers, $bodyContent);
    }
}