<?php
namespace Nap;

class Application
{
    // Dependencies
    /** @var Resource\ResourceMatcher */
    private $matcher;
    /** @var Controller\ControllerResolutionStrategy */
    private $resolver;
    /** @var Controller\ControllerBuilderStrategy*/
    private $builder;
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
        $this->resources = array();

        $this->controllerNamespace = "";
    }

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

        $this->dispatchMethod($controller, $request, $matchedResource->getParameters());
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

    private function dispatchMethod(
        \Nap\Controller\NapControllerInterface $controller,
        \Symfony\Component\HttpFoundation\Request $request,
        array $parameters
    ) {
        $controllerMethodCall = function($method) use($controller, $request, $parameters){
            $controller->{$method}($request, $parameters);
        };

        $controllerIndexCall = function() use($controller, $request){
            $controller->index($request);
        };

        switch(strtolower($request->getMethod()))
        {
            case "get":
                if(count($parameters) === 0){
                    $controllerIndexCall();
                    break;
                }

                $controllerMethodCall("get");
                break;

            case "post":
                $controllerMethodCall("post");
                break;

            case "put":
                $controllerMethodCall("put");
                break;

            case "delete":
                $controllerMethodCall("delete");
                break;

            case "options":
                $controllerMethodCall("options");
                break;
        }
    }
}