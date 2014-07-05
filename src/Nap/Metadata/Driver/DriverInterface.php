<?php
namespace Nap\Metadata\Driver;


interface DriverInterface
{
    /**
     * @param \ReflectionClass $class
     * @return \Nap\Metadata\ControllerMetadata
     */
    public function getControllerMetadata(\ReflectionClass $class);
} 
