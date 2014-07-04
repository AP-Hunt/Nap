<?php
namespace Nap\Test\Resource;

class ResourceMatcherTest extends \PHPUnit_Framework_TestCase
{
    private $matchableResourceUriBuilder;

    /** @var \Nap\Resource\ResourceMatcher */
    private $matcher;

    public function setUp()
    {
        $this->matchableResourceUriBuilder = $this->getMock("\Nap\Uri\MatchableUriBuilderInterface");
        $this->matcher = new \Nap\Resource\ResourceMatcher($this->matchableResourceUriBuilder);
    }

    /** @test **/
    public function WhenGivenPathWhichCannotBeMatched_ReturnsNull()
    {
        // Arrange
        $rootResource = new \Nap\Resource\Resource("MyResource", "", null);
        $this->expectPathsFromUriBuilder($rootResource, array());

        // Act
        $matchedResource = $this->matcher->match("/some/path", array($rootResource));

        // Assert
        $this->assertNull($matchedResource);
    }

    /** @test */
    public function WhenGivenPathWhichCanBeMatched_ReturnsMatchedResource()
    {
        // Arrange
        $rootResource = new \Nap\Resource\Resource("MyResource", "", null);

        $uri = "/some/path";
        $matchable = $this->createMockMatchableUri();
        $matchable->expects($this->once())
                ->method("matches")
                ->with($uri)
                ->will($this->returnValue(true));

        $matchable->expects($this->once())
                ->method("getResource")
                ->will($this->returnValue(new \Nap\Resource\Resource("SomeResource", "/some/path", null)));

        $matchable->expects($this->once())
                ->method("getParameters")
                ->will($this->returnValue(array()));

        $this->expectPathsFromUriBuilder($rootResource, array($matchable));

        // Act
        $matchedResource = $this->matcher->match("/some/path", array($rootResource));

        // Assert
        $this->assertInstanceOf("\Nap\Resource\MatchedResource", $matchedResource);
    }

    /** @test */
    public function WhenPathIsMatched_AndHasEventDispatcher_DispatchesFoundEvent()
    {
        // Arrange
        $rootResource = new \Nap\Resource\Resource("MyResource", "", null);

        $uri = "/some/path";
        $matchable = new \Nap\Test\Resource\Stubs\MatchableUri($rootResource);
        $this->expectPathsFromUriBuilder($rootResource, array($matchable));

        $mockEventDispatcher = $this->getMock("\Symfony\Component\EventDispatcher\EventDispatcherInterface");
        $mockEventDispatcher->expects($this->once())
                ->method("dispatch")
                ->with(
                    \Nap\Events\ResourceMatchingEvents::RESOURCE_MATCHED,
                    $this->isInstanceOf("\Nap\Events\ResourceMatching\ResourceMatchedEvent")
                );

        $this->matcher->setEventDispatcher($mockEventDispatcher);

        // Act
        $this->matcher->match($uri, array($rootResource));
    }

    /** @test */
    public function WhenPathIsNotMatched_AndHasEventDispatcher_DispatchesNotFoundEvent()
    {
        // Arrange
        $rootResource = new \Nap\Resource\Resource("MyResource", "", null);

        $uri = "/some/path";
        $this->expectPathsFromUriBuilder($rootResource, array());

        $mockEventDispatcher = $this->getMock("\Symfony\Component\EventDispatcher\EventDispatcherInterface");
        $mockEventDispatcher->expects($this->once())
            ->method("dispatch")
            ->with(
                \Nap\Events\ResourceMatchingEvents::RESOURCE_NOT_MATCHED,
                $this->isInstanceOf("\Nap\Events\ResourceMatching\ResourceNotMatchedEvent")
            );

        $this->matcher->setEventDispatcher($mockEventDispatcher);

        // Act
        $this->matcher->match($uri, array($rootResource));
    }

    private function createMockMatchableUri()
    {
        return $this->getMockBuilder("\Nap\Uri\MatchableUri")
                ->disableOriginalConstructor()
                ->getMock();
    }

    private function expectPathsFromUriBuilder(
        \Nap\Resource\Resource $resource,
        array $array
    ) {
        $this->matchableResourceUriBuilder->expects($this->once())
                ->method("buildUrisForResource")
                ->with($resource)
                ->will($this->returnValue($array));
    }
} 