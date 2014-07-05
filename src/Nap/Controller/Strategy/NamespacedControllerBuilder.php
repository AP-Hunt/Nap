<?php
namespace Nap\Controller\Strategy;

use Nap\Controller\ControllerResolutionStrategy;
use Nap\Resource\Resource;

class NamespacedControllerBuilder implements \Nap\Controller\ControllerBuilderStrategy
{
    /**
     * @var \Nap\Controller\ControllerResolutionStrategy
     */
    private $resolver;

    public function __construct(ControllerResolutionStrategy $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Builds a Nap controller given a resource
     *
     * @param   Resource $resource
     * @return  \Nap\Controller\NapControllerInterface
     */
    public function buildController(Resource $resource)
    {
        $controllerFQN = $this->resolver->resolve($resource);
        return new $controllerFQN();
    }

    /**
     * Set the root namespace for controllers
     *
     * @param   string $FQN
     * @return  void
     */
    public function setControllerRootNamesapce($FQN)
    {
        $this->resolver->setControllerRootNamesapce($FQN);
    }
}