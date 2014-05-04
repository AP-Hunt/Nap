<?php
namespace Nap;

class Application
{
    /** @var Resource\ResourceMatcher */
    private $matcher;
    /** @var Controller\ControllerResolutionStrategy */
    private $resolver;
    /** @var Controller\ControllerBuilderStrategy*/
    private $builder;
    /** @var Resource\Resource[] */
    private $resources;

    public function __construct(
        \Nap\Resource\ResourceMatcher $matcher,
        \Nap\Controller\ControllerResolutionStrategy $resolver,
        \Nap\Controller\ControllerBuilderStrategy $builder
    ) {

        $this->matcher = $matcher;
        $this->resolver = $resolver;
        $this->builder = $builder;
        $this->resources = array();
    }

    /**
     * @param Resource\Resource[] $resources
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
    }

    public function start(\Symfony\Component\HttpFoundation\Request $request)
    {
        $uri = $request->getPathInfo();

        $matchedResource = $this->findResourceForUri($uri);
        if($matchedResource === null){
            throw new \Nap\Resource\NoMatchingResourceException();
        }

        $controllerPath = $this->resolver->resolve($matchedResource);
        $controller = $this->builder->buildController($controllerPath);

        if(!($controller instanceof \Nap\Controller\NapControllerInterface)){
            throw new \Nap\Controller\InvalidControllerException();
        }

        $this->dispatchMethod($controller, $request);
    }

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
        \Symfony\Component\HttpFoundation\Request $request
    ) {
        switch(strtolower($request->getMethod()))
        {
            case "get":
                $controller->get($request);
                break;

            case "post":
                $controller->post($request);
                break;

            case "put":
                $controller->put($request);
                break;

            case "delete":
                $controller->delete($request);
                break;

            case "options":
                $controller->options($request);
                break;
        }
    }
}