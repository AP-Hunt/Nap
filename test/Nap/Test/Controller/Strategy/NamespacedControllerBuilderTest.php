<?php
namespace Nap\Test\Controller\Strategy;

class NamespacedControllerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \SplClassLoader */
    private $classLoader;

    public function setUp()
    {
        $this->classLoader = new \SplClassLoader("ControllerTest", realpath(__DIR__."/namespace/"));
        $this->classLoader->register();
    }

    public function tearDown()
    {
        $this->classLoader->unregister();
    }

    /** @test **/
    public function CorrectlyConvertsPathInToNamespace()
    {
        // Arrange
        $path = str_replace("/", "\\", "/ControllerTest/Parent/ChildController");
        $expectedType = "\ControllerTest\Parent\ChildController";

        $builder = new \Nap\Controller\Strategy\NamespacedControllerBuilder();

        // Act
        $controller = $builder->buildController($path);

        // Assert
        $this->assertInstanceOf($expectedType, $controller);
    }
} 