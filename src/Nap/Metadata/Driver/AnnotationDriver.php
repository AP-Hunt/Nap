<?php
namespace Nap\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Nap\Metadata\ControllerMetadata;
use Nap\Metadata\Annotations;
use Doctrine\Common\Annotations\AnnotationReader;

class AnnotationDriver implements DriverInterface
{
    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    private $annotationsReader;

    public function __construct(AnnotationReader $annotationsReader)
    {
        $this->annotationsReader = $annotationsReader;
        AnnotationRegistry::registerAutoloadNamespace("Nap\Metadata\Annotations", array(__DIR__."/../../../"));
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return \Nap\Metadata\ControllerMetadata
     */
    public function getControllerMetadata(\ReflectionClass $class)
    {
        if(!$class->implementsInterface("\Nap\Controller\NapControllerInterface"))
        {
            throw new \InvalidArgumentException("Class must implement \Nap\Controller\NapControllerInterface");
        }

        $controllerMetadata = new ControllerMetadata($class->getName());
        $methods = array_map(
            function($methodName) use($class){
                return $class->getMethod($methodName);
            },
            array("index", "get", "post", "put", "delete", "options")
        );
        foreach($methods as $method)
        {
            $this->setAcceptedMimeTypes($method, $controllerMetadata);
            $this->setDefaultMimeTypes($method, $controllerMetadata);
        }

        return $controllerMetadata;
    }

    private function setAcceptedMimeTypes(\ReflectionMethod $method, ControllerMetadata $metadata)
    {
        /** @var Annotations\Accept $annot */
        $annot = $this->annotationsReader->getMethodAnnotation($method, "Nap\Metadata\Annotations\Accept");

        if($annot === null) {
            $metadata->setAcceptedMimeTypes($method->getName(), array("*/*"));
            return;
        }

        $mimeTypes = $annot->getAcceptedMimeTypes();
        $metadata->setAcceptedMimeTypes($method->getName(), $mimeTypes);
    }

    private function setDefaultMimeTypes(\ReflectionMethod $method, ControllerMetadata $controllerMetadata)
    {
        /** @var Annotations\DefaultMime $annot */
        $annot = $this->annotationsReader->getMethodAnnotation($method, "Nap\Metadata\Annotations\DefaultMime");

        if($annot === null) {
            $controllerMetadata->setDefaultMimeType($method->getName(), null);
            return;
        }

        $mimeType = $annot->getDefaultMimeType();
        $controllerMetadata->setDefaultMimeType($method->getName(), $mimeType);
    }
}
