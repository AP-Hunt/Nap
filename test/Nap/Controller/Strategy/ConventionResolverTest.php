<?php

class ConventionResolverTest extends PHPUnit_Framework_TestCase
{
    /** @var \Nap\Controller\Strategy\ConventionResolver */
    private $resolver;

    public function setUp()
    {
        $this->resolver = new \Nap\Controller\Strategy\ConventionResolver();
    }

    public function tearDown()
    {
        $this->resolver = null;
    }

    /** @test **/
    public function AppendsControllerNamespace_ToResolvedController()
    {
        // Arrange
        $namespace = "\My\Namespace";
        $resource = new \Nap\Resource\Resource("MyResource", "", null);
        $expectedFQN = "\My\Namespace\MyResourceController";

        // Act
        $fqn = $this->resolver->resolve($namespace, $resource);

        // Assert
        $this->assertEquals($expectedFQN, $fqn);
    }

    /** @test **/
    public function WhenResourceHasNoParent_LooksForResourceNamePlusController()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("MyResource", "", null);
        $expectedFQN = str_replace("/", DIRECTORY_SEPARATOR, "/MyResourceController");

        // Act
        $fqn = $this->resolver->resolve("", $resource);

        // Assert
        $this->assertEquals($expectedFQN, $fqn);
    }

    /** @test **/
    public function WhenResourceIsChild_LooksForResourceInParentNameFolder()
    {
        // Arrange
        $child = new \Nap\Resource\Resource("Child", "", null);
        $child->setParent(new \Nap\Resource\Resource("Parent", "", null));
        $expectedFQN = str_replace("/", DIRECTORY_SEPARATOR, "/Parent/ChildController");

        // Act
        $fqn = $this->resolver->resolve("", $child);

        // Assert
        $this->assertEquals($expectedFQN, $fqn);
    }

    /** @test **/
    public function WhenResourceIsGrandChild_LooksForResourceInParentFolder_InGrandParentNameFolder()
    {
        // Arrange
        $grandParent = new \Nap\Resource\Resource("Grandparent", "", null);

        $parent = new \Nap\Resource\Resource("Parent", "", null);
        $parent->setParent($grandParent);

        $child = new \Nap\Resource\Resource("Child", "", null);
        $child->setParent($parent);

        $expectedFQN = str_replace("/", DIRECTORY_SEPARATOR, "/Grandparent/Parent/ChildController");

        // Act
        $fqn = $this->resolver->resolve("", $child);

        // Assert
        $this->assertEquals($expectedFQN, $fqn);
    }
}