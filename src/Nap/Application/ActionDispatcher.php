<?php
namespace Nap\Application;


use Nap\Controller\ResultInterface;
use Nap\Events\ActionDispatcherEvents;
use Nap\Metadata\ControllerMetadataProvider;
use Nap\Resource\MatchedResource;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class ActionDispatcher
{
    use \Nap\Events\EventedTrait;

    /** @var \Nap\Controller\ControllerBuilderStrategy */
    private $builder;

    /** @var \Nap\Metadata\ControllerMetadataProvider */
    private $metadataProvider;

    /** @var ContentNegotiatorInterface */
    private $contentNegotiator;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        \Nap\Controller\ControllerBuilderStrategy $builder,
        ControllerMetadataProvider $metadataProvider,
        ContentNegotiatorInterface $contentNegotiator
    ) {
        $this->setEventDispatcher($eventDispatcher);
        $this->builder = $builder;
        $this->metadataProvider = $metadataProvider;
        $this->contentNegotiator = $contentNegotiator;
    }

    public function dispatch(
        Request $request,
        MatchedResource $resource
    ) {
        $controller = $this->builder->buildController($resource->getResource());
        if(!($controller instanceof \Nap\Controller\NapControllerInterface))
        {
            throw new \Nap\Controller\InvalidControllerException();
        }

        $controllerMethod = $this->getControllerMethod($request->getMethod(), count($resource->getParameters()));
        $acceptHeader = $request->headers->get("Accept");
        $controllerMetadata = $this->metadataProvider->getMetadataFor(get_class($controller));

        $bestMatch = $this->findBestMatchingMimeType($acceptHeader, $controllerMetadata, $controllerMethod);

        if($bestMatch === null) {
            $event = new \Nap\Events\ActionDispatcher\NoAppropriateResponseEvent();
            $this->dispatchEvent(ActionDispatcherEvents::NO_APPROPRIATE_RESPONSE, $event);
            return;
        }

        $result = $this->callControllerMethod(
            $controllerMethod,
            $controller,
            $request,
            $resource->getParameters()
        );

        $evt = new \Nap\Events\ActionDispatcher\ControllerExecutedEvent(
            $result,
            $bestMatch
        );

        $this->dispatchEvent(
            ActionDispatcherEvents::CONTROLLER_EXECUTED,
            $evt
        );
    }

    /**
     * Finds the right controller method to call.
     *
     * @param   string                                    $requestMethod
     * @param   int                                       $numberOfParameters
     * @return  string
     */
    private function getControllerMethod(
        $requestMethod,
        $numberOfParameters
    ) {
        switch(strtolower($requestMethod))
        {
            case "get":
                if($numberOfParameters === 0){
                    return "index";
                    break;
                }

                return "get";
                break;

            case "post":
                return "post";
                break;

            case "put":
                return "put";
                break;

            case "delete":
                return "delete";
                break;

            case "options":
                return "options";
                break;
        }
    }


    /**
     * @param string    $acceptHeader
     * @param \Nap\Metadata\ControllerMetadata $controllerMetadata
     * @param string    $method
     * @return null|string
     * @throws \Nap\Metadata\MissingDefaultResultTypeException
     */
    private function findBestMatchingMimeType(
        $acceptHeader,
        \Nap\Metadata\ControllerMetadata $controllerMetadata,
        $method)
    {
        $bestMatch = null;
        if ($acceptHeader === null
            && $controllerMetadata->getDefaultMimeType($method) === null
        ) {
            throw new \Nap\Metadata\MissingDefaultResultTypeException();
        }
        else if ($acceptHeader === null)
        {
            $bestMatch = $controllerMetadata->getDefaultMimeType($method);
        }
        else {
            $bestMatch = $this->contentNegotiator->getBestMatch(
                $acceptHeader,
                $controllerMetadata->getAcceptedMimeTypes($method)
            );
        }
        return $bestMatch;
    }

    /**
     * @param   string                                    $controllerMethod
     * @param   \Nap\Controller\NapControllerInterface    $controller
     * @param   Request                                   $request
     * @param   array $parameters
     * @return  ResultInterface
     */
    private function callControllerMethod(
        $controllerMethod,
        \Nap\Controller\NapControllerInterface $controller,
        Request $request,
        array $parameters)
    {
        return $controller->{$controllerMethod}($request, $parameters);
    }
} 