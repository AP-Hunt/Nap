<?php
namespace Nap\Controller;

interface ControllerResolutionStrategy
{
    /**
     * Resolves a given resource to its controller
     *
     * @param \Nap\Resource\Resource $resource
     * @return \Nap\Controller\NapControllerInterface
     */
    public function resolve(\Nap\Resource\Resource $resource);
} 