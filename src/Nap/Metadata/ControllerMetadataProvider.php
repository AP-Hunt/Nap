<?php
namespace Nap\Metadata;

use Nap\Metadata\Driver\DriverInterface;

class ControllerMetadataProvider
{
    /** @var Driver\DriverInterface */
    private $driver;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param   string  $type
     * @return  ControllerMetadata
     */
    public function getMetadataFor($type)
    {
        $reflClass = new \ReflectionClass($type);
        return $this->driver->getControllerMetadata($reflClass);
    }
} 
