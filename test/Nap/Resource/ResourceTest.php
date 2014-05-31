<?php

use \Nap\Resource\Parameter\Scheme;

class ResourceTest extends PHPUnit_Framework_TestCase
{
    /** @test **/
    public function CreateResource_WithName()
    {
        // Arrange
        $name = "MyResource";

        // Act
        $resource = new \Nap\Resource\Resource($name, "", null);

        // Assert
        $this->assertEquals($name, $resource->getName());
    }
    
    /** @test **/
    public function CreateResource_WithUriPartialRegex()
    {
        // Arrange
        $pathRegex = "part/of/path/\d+";

        // Act
        $resource = new \Nap\Resource\Resource("MyResource", $pathRegex, null);
        
        // Assert
        $this->assertEquals($pathRegex, $resource->getUriPartial());
    }

    /** @test **/
    public function CreateResource_WithNoChildren()
    {
        // Arrange
        $expectedCount = 0;

        // Act
        $resource = new \Nap\Resource\Resource("MyResource", "", null);

        // Assert
        $this->assertEquals($expectedCount, count($resource->getChildResources()));
    }

    /** @test **/
    public function CreateResource_WithChildren()
    {
        // Arrange
        $expectedCount = 1;
        $children = array(
            new \Nap\Resource\Resource("ChildResource", "", null)
        );
        // Act
        $resource = new \Nap\Resource\Resource("MyResource", "", null, $children);

        // Assert
        $this->assertEquals($expectedCount, count($resource->getChildResources()));
        $this->assertEquals($children, $resource->getChildResources());
    }

    /** @test **/
    public function CreateResource_AndSetParent()
    {
        // Arrange
        $parent = new \Nap\Resource\Resource("Parent", "", null);

        // Act
        $child = new \Nap\Resource\Resource("Child", "", null);
        $child->setParent($parent);

        // Assert
        $this->assertEquals($parent, $child->getParent());
    }

    /** @test **/
    public function CreateResource_WithNullParameterScheme()
    {
        // Arrange
        $expectedType = "\Nap\Resource\Parameter\Scheme\None";
        $resource = new \Nap\Resource\Resource("My", "", null);

        // Act
        $scheme = $resource->getParameterScheme();

        // Assert
        $this->assertInstanceOf($expectedType, $scheme);
    }

    /** @test **/
    public function WhenAddingResourceAsChild_SetsResourcesParent()
    {
        // Arrange
        $child = new \Nap\Resource\Resource("Child", "", null);

        // Act
        $parent = new \Nap\Resource\Resource("Parent", "", null, array($child));

        // Assert
        $this->assertEquals($parent, $child->getParent());
    }

    /** @test **/
    public function GetParameters_ReturnsJustOwnParameters()
    {
        // Arrange
        $scheme = new Scheme\Id();
        $expected = $scheme->getParameters();
        $resource = new \Nap\Resource\Resource("Resource", "/resource", $scheme);

        // Act
        $actual = $resource->getParameters();

        // Assert
        $this->assertEquals($expected, $actual);
    }
}