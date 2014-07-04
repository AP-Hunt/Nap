<?php
namespace Nap\Test\Controller\Strategy;

use Nap\Resource\Resource;

class NamespacedControllerBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \SplClassLoader */
    private $classLoader;

    private $resolver;

    public function setUp()
    {
        $this->classLoader = new \SplClassLoader("ControllerTest", realpath(__DIR__."/namespace/"));
        $this->classLoader->register();

        $this->resolver = $this->getMock("\Nap\Controller\ControllerResolutionStrategy");
    }

    public function tearDown()
    {
        $this->classLoader->unregister();
        $this->resolver = null;
    }

    /** @test **/
    public function CorrectlyConvertsResourceInToController()
    {
        // Arrange
        $fqn = str_replace("/", "\\", "/ControllerTest/Parent/ChildController");
        $expectedType = "\ControllerTest\Parent\ChildController";

        $parentResource = new Resource("Parent", "/parent");
        $childResource = new Resource("Child", "/child", null, array($parentResource));

        $this->resolver->expects($this->once())
                ->method("resolve")
                ->with($childResource)
                ->will($this->returnValue($fqn));

        $builder = new \Nap\Controller\Strategy\NamespacedControllerBuilder($this->resolver);

        // Act
        $controller = $builder->buildController($childResource);

        // Assert
        $this->assertInstanceOf($expectedType, $controller);
    }
} 