<?php
namespace Nap\Controller;

use Nap\Resource\Resource;

interface ControllerBuilderStrategy
{
    /**
     * Builds a Nap controller given a resource
     *
     * @param   Resource $resource
     * @return  \Nap\Controller\NapControllerInterface
     */
    public function buildController(Resource $resource);

    /**
     * Set the root namespace for controllers
     *
     * @param   string    $FQN
     * @return  void
     */
    public function setControllerRootNamesapce($FQN);
} 