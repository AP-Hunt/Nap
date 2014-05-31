<?php
namespace Nap\Controller;

interface ControllerBuilderStrategy
{
    /**
     * Builds a Nap controller given a FQN
     *
     * @param   string  $controllerFQN
     * @return  \Nap\Controller\NapControllerInterface
     */
    public function buildController($controllerFQN);
} 