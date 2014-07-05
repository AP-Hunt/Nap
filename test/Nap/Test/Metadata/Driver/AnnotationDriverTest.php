<?php
namespace Nap\Test\Metadata\Driver;


use Doctrine\Common\Annotations\AnnotationReader;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function findsAllAcceptAnnotationsOnMethods()
    {
        // Arrange
        // Values as specified in annotations of stub class
        // \Nap\Test\Metadata\Controllers\Controller
        $expected = array(
            "get" => array("application/json", "application/xml"),
            "index" => array("application/index"),
            "post" => array("application/post"),
            "put" => array("application/put"),
            "delete" => array("application/delete"),
            "options" => array("application/options")
        );

        $relfClass = new \ReflectionClass("\Nap\Test\Metadata\Controllers\Controller");
        $driver = new \Nap\Metadata\Driver\AnnotationDriver(new AnnotationReader());

        // Act
        /** @var \Nap\Metadata\ControllerMetadata $actual */
        $actual = $driver->getControllerMetadata($relfClass);

        // Assert
        $this->assertEquals($expected["index"], $actual->getAcceptedMimeTypes("index"));
        $this->assertEquals($expected["get"], $actual->getAcceptedMimeTypes("get"));
        $this->assertEquals($expected["post"], $actual->getAcceptedMimeTypes("post"));
        $this->assertEquals($expected["put"], $actual->getAcceptedMimeTypes("put"));
        $this->assertEquals($expected["delete"], $actual->getAcceptedMimeTypes("delete"));
        $this->assertEquals($expected["options"], $actual->getAcceptedMimeTypes("options"));
    }

    /** @test **/
    public function methodAcceptsAllMimetypesWhenNotSpecifyingAnyInAnnotations()
    {
        // Arrange
        // Values as specified in annotations of stub class
        // \Nap\Test\Metadata\Controllers\ControllerWithoutAnnotations
        $expected = array(
            "get" => array("*/*"),
        );

        $relfClass = new \ReflectionClass("\Nap\Test\Metadata\Controllers\ControllerWithoutAnnotations");
        $driver = new \Nap\Metadata\Driver\AnnotationDriver(new AnnotationReader());

        // Act
        /** @var \Nap\Metadata\ControllerMetadata $actual */
        $actual = $driver->getControllerMetadata($relfClass);

        // Assert
        $this->assertEquals($expected["get"], $actual->getAcceptedMimeTypes("get"));
    }

    /** @test **/
    public function findsAllDefaultAnnoationsOnMethods()
    {
        // Arrange
        // Values as specified in annotations of stub class
        // \Nap\Test\Metadata\Controllers\Controller
        $expected = array(
            "get" => "default/get",
            "index" => "default/index",
            "post" => "default/post",
            "put" => "default/put",
            "delete" => "default/delete",
            "options" => "default/options"
        );

        $relfClass = new \ReflectionClass("\Nap\Test\Metadata\Controllers\Controller");
        $driver = new \Nap\Metadata\Driver\AnnotationDriver(new AnnotationReader());

        // Act
        /** @var \Nap\Metadata\ControllerMetadata $actual */
        $actual = $driver->getControllerMetadata($relfClass);

        // Assert
        $this->assertEquals($expected["index"], $actual->getDefaultMimeType("index"));
        $this->assertEquals($expected["get"], $actual->getDefaultMimeType("get"));
        $this->assertEquals($expected["post"], $actual->getDefaultMimeType("post"));
        $this->assertEquals($expected["put"], $actual->getDefaultMimeType("put"));
        $this->assertEquals($expected["delete"], $actual->getDefaultMimeType("delete"));
        $this->assertEquals($expected["options"], $actual->getDefaultMimeType("options"));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     **/
    public function throwsExceptionIfClassIsNotAController()
    {
        // Arrange
        $reflClass = new \ReflectionClass("StdClass");
        $driver = new \Nap\Metadata\Driver\AnnotationDriver(new AnnotationReader());

        // Act
        $driver->getControllerMetadata($reflClass);
    }
} 
