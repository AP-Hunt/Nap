<?php
namespace Nap\Controller;

interface ControllerResolutionStrategy
{
    /**
     * Resolves a given resource to its controller
     *
     * @param \Nap\Resource\Resource    $resource
     * @return string   The FQN of the resource's controller
     */
    public function resolve(\Nap\Resource\Resource $resource);
}