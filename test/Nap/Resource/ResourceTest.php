<?php


class ResourceTest extends PHPUnit_Framework_TestCase
{
    /** @test **/
    public function CreateResource_WithName()
    {
        // Arrange
        $name = "MyResource";

        // Act
        $resource = new \Nap\Resource\Resource($name, "");

        // Assert
        $this->assertEquals($name, $resource->getName());
    }
    
    /** @test **/
    public function CreateResource_WithPathPartialRegex()
    {
        // Arrange
        $pathRegex = "part/of/path/\d+";

        // Act
        $resource = new \Nap\Resource\Resource("MyResource", $pathRegex);
        
        // Assert
        $this->assertEquals($pathRegex, $resource->getPathPartial());
    }

    /** @test **/
    public function CreateResource_WithNoChildren()
    {
        // Arrange
        $expectedCount = 0;

        // Act
        $resource = new \Nap\Resource\Resource("MyResource", "");

        // Assert
        $this->assertEquals($expectedCount, count($resource->getChildResources()));
    }

    /** @test **/
    public function CreateResource_WithChildren()
    {
        // Arrange
        $expectedCount = 1;
        $children = array(
            new \Nap\Resource\Resource("ChildResource", "")
        );
        // Act
        $resource = new \Nap\Resource\Resource("MyResource", "", $children);

        // Assert
        $this->assertEquals($expectedCount, count($resource->getChildResources()));
        $this->assertEquals($children, $resource->getChildResources());
    }

    /** @test **/
    public function CreateResource_AndSetParent()
    {
        // Arrange
        $parent = new \Nap\Resource\Resource("Parent", "");

        // Act
        $child = new \Nap\Resource\Resource("Child", "");
        $child->setParent($parent);

        // Assert
        $this->assertEquals($parent, $child->getParent());
    }

    /** @test **/
    public function WhenAddingResourceAsChild_SetsResourcesParent()
    {
        // Arrange
        $child = new \Nap\Resource\Resource("Child", "");

        // Act
        $parent = new \Nap\Resource\Resource("Parent", "", array($child));

        // Assert
        $this->assertEquals($parent, $child->getParent());
    }
} 