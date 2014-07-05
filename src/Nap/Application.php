<?php
namespace Nap;


use Nap\Application\ActionDispatcher;
use Nap\Application\ResourceMatchMediator;
use Nap\Application\ResponseMediator;
use Nap\Events\ResourceMatchingEvents;
use Nap\Resource\ResourceMatcher;
use Nap\Response\Responder;
use Nap\Serialisation\SerialiserRegistry;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class Application
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcher */
    private $eventDispatcher;

    /** @var Resource\ResourceMatcher */
    private $matcher;

    /** @var Application\ActionDispatcher */
    private $actionDispatcher;

    /** @var Response\Responder */
    private $responder;

    /** @var ResponseMediator */
    private $responseMediator;

    /** @var ResourceMatchMediator  */
    private $resourceMatchMediator;

    /** @var Serialisation\SerialiserRegistry */
    private $serialiserRegistry;

    public function __construct(
        EventDispatcher $eventDispatcher,
        ResourceMatcher $matcher,
        ActionDispatcher $actionDispatcher,
        SerialiserRegistry $serialiserRegistry,
        Responder $responder
     ) {
        $this->eventDispatcher = $eventDispatcher;

        $this->matcher = $matcher;
        $this->actionDispatcher = $actionDispatcher;
        $this->responder = $responder;
        $this->serialiserRegistry = $serialiserRegistry;

        $this->actionDispatcher->setEventDispatcher($this->eventDispatcher);
        $this->matcher->setEventDispatcher($this->eventDispatcher);

        $this->responseMediator = new ResponseMediator(
            $this->responder,
            $this->serialiserRegistry
        );

        $this->resourceMatchMediator = new ResourceMatchMediator($this->actionDispatcher);

        $this->eventDispatcher->addSubscriber($this->responseMediator);
        $this->eventDispatcher->addSubscriber($this->resourceMatchMediator);
    }

    /**
     * Starts the application, matching the URI to a resource and generating a response
     *
     * @param Request       $request
     * @param Resource[]    $resources
     */
    public function start(Request $request, array $resources)
    {
        $this->resourceMatchMediator->setRequest($request);

        // Kicks off the chain of events leading to a response
        $this->matcher->match($request->getPathInfo(), $resources);
    }
}