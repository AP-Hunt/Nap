<?php

class ConventionResolverTest extends PHPUnit_Framework_TestCase
{
    /** @var \Nap\Controller\Strategy\ConventionResolver */
    private $resolver;
    private $fileLoader;

    public function setUp()
    {
        $this->fileLoader = $this->getMockBuilder("\Nap\Util\FileLoader")
                                ->disableOriginalConstructor()
                                ->getMock();
        $this->resolver = new \Nap\Controller\Strategy\ConventionResolver($this->fileLoader);
    }

    public function tearDown()
    {
        $this->resolver = null;
    }

    /** @test **/
    public function WhenResourceHasNoParent_LooksForResourceNamePlusControllerFile()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("MyResource", "");
        $expectedRelativePath = str_replace("/", DIRECTORY_SEPARATOR, "/MyResourceController.php");

        $this->fileLoader->expects($this->once())
                    ->method("loadFile")
                    ->with($expectedRelativePath);

        // Act
        $path = $this->resolver->resolve($resource);

        // Assert
        $this->assertEquals($expectedRelativePath, $path);
    }

    /** @test **/
    public function WhenResourceIsChild_LooksForResourceInParentNameFolder()
    {
        // Arrange
        $child = new \Nap\Resource\Resource("Child", "");
        $child->setParent(new \Nap\Resource\Resource("Parent", ""));
        $expectedRelativePath = str_replace("/", DIRECTORY_SEPARATOR, "/Parent/ChildController.php");

        $this->fileLoader->expects($this->once())
            ->method("loadFile")
            ->with($expectedRelativePath);

        // Act
        $path = $this->resolver->resolve($child);

        // Assert
        $this->assertEquals($expectedRelativePath, $path);
    }

    /** @test **/
    public function WhenResourceIsGrandChild_LooksForResourceInParentFolder_InGrandParentNameFolder()
    {
        // Arrange
        $grandParent = new \Nap\Resource\Resource("Grandparent", "");

        $parent = new \Nap\Resource\Resource("Parent", "");
        $parent->setParent($grandParent);

        $child = new \Nap\Resource\Resource("Child", "");
        $child->setParent($parent);

        $expectedRelativePath = str_replace("/", DIRECTORY_SEPARATOR, "/Grandparent/Parent/ChildController.php");

        $this->fileLoader->expects($this->once())
            ->method("loadFile")
            ->with($expectedRelativePath);

        // Act
        $path = $this->resolver->resolve($child);

        // Assert
        $this->assertEquals($expectedRelativePath, $path);
    }
}