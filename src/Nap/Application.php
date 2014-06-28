<?php
namespace Nap;

use Nap\Serialisation;
use Nap\Serialisation\SerialiserInterface;

class Application
{
    // Dependencies
    /** @var Resource\ResourceMatcher */
    private $matcher;
    /** @var Controller\ControllerResolutionStrategy */
    private $resolver;
    /** @var Controller\ControllerBuilderStrategy*/
    private $builder;
    /** @var Application\Dispatcher */
    private $dispatcher;
    /** @var Application\Responder */
    private $responder;
    /** @var Application\ContentNegotiatorInterface */
    private $contentNegotiator;

    // Settings
    /** @var Resource\Resource[] */
    private $resources;
    // Configuration
    private $controllerNamespace;

    public function __construct(
        \Nap\Resource\ResourceMatcher $matcher,
        \Nap\Controller\ControllerResolutionStrategy $resolver,
        \Nap\Controller\ControllerBuilderStrategy $builder
    ) {

        $this->matcher = $matcher;
        $this->resolver = $resolver;
        $this->builder = $builder;
        $this->dispatcher = new Application\Dispatcher();
        $this->responder = new Application\Responder();
        $this->contentNegotiator = new Application\ContentNegotiation();

        $this->resources = array();
        $this->controllerNamespace = "";

        $this->registerDefaultHandlers();
    }

    /**
     * Application methods
     */

    private function registerDefaultHandlers()
    {
        $this->responder->registerSerialiser(
            "application/json",
            new Serialisation\JSON()
        );
    }

    /**
     * Start a Nap application. Routes requests to controllers and handles response.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws Resource\NoMatchingResourceException
     * @throws Controller\InvalidControllerException
     */
    public function start(\Symfony\Component\HttpFoundation\Request $request)
    {
        $uri = $request->getPathInfo();

        $matchedResource = $this->findResourceForUri($uri);
        if($matchedResource === null){
            throw new \Nap\Resource\NoMatchingResourceException();
        }

        $controllerPath = $this->resolver->resolve($this->controllerNamespace, $matchedResource->getResource());
        $controller = $this->builder->buildController($controllerPath);

        if(!($controller instanceof \Nap\Controller\NapControllerInterface)){
            throw new \Nap\Controller\InvalidControllerException();
        }

        /** @var \Nap\Controller\ResultInterface $data */
        $data = $this->dispatcher->dispatchMethod($controller, $request, $matchedResource->getParameters());

        $this->responder->respond(
            $request,
            $this->contentNegotiator,
            $data
        );
    }

    /**
     * @param $uri
     * @return Resource\MatchedResource|null
     */
    private function findResourceForUri($uri)
    {
        foreach($this->resources as $root)
        {
            $r = $this->matcher->match($uri, $root);
            if($r !== null){
                return $r;
            }
        }

        return null;
    }

    /**
     * Configuration methods
     */

    /**
     * @param Resource\Resource[] $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    public function setControllerNamespace($namespace)
    {
        $this->controllerNamespace = $namespace;
    }

    /**
     * @param \Nap\Application\Dispatcher $dispatcher
     */
    public function setDispatcher(\Nap\Application\Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param \Nap\Application\ContentNegotiatorInterface $contentNegotiator
     */
    public function setContentNegotiator($contentNegotiator)
    {
        $this->contentNegotiator = $contentNegotiator;
    }

    public function registerMimeTypeSerialiser($mimeType, SerialiserInterface $serialiser)
    {
        $this->responder->registerSerialiser($mimeType, $serialiser);
    }
}