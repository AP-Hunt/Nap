<?php
namespace Nap\Controller;

interface ControllerBuilderStrategy
{
    /**
     * Builds a Nap controller given a relative path to one
     *
     * @param $controllerRelativePath
     * @return \Nap\Controller\NapControllerInterface
     */
    public function buildController($controllerRelativePath);
} 