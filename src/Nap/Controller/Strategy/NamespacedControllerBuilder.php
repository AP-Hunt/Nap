<?php
namespace Nap\Controller\Strategy;

class NamespacedControllerBuilder implements \Nap\Controller\ControllerBuilderStrategy
{

    /**
     * Builds a Nap controller given a FQN
     *
     * @param   string $controllerFQN
     * @return  \Nap\Controller\NapControllerInterface
     */
    public function buildController($controllerFQN)
    {
        return new $controllerFQN();
    }
}