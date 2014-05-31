<?php

class MatchableUriTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function getParameters_ReturnsEmptyArrayWhenUrisDontMatch()
    {
        // Arrange
        $uri = "does not match";
        $regex = "#^/resource/(?P<id>\d+)$#";
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleIntParam());
        $matchableUri = new \Nap\Uri\MatchableUri($regex, $resource);

        // Act
        $actualArray = $matchableUri->getParameters($uri);

        // Assert
        $expectedArray = array();

        $this->assertSame($expectedArray, $actualArray);
    }

    /** @test **/
    public function getParameters_ReturnsConvertedMatchedParameterValues_UsingParameterConverterMethod()
    {
        // Arrange
        $uri = "/resource/1";
        $regex = "#^/resource/(?P<id>\d+)$#";
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleIntParam());
        $matchableUri = new \Nap\Uri\MatchableUri($regex, $resource);

        // Act
        $params = $matchableUri->getParameters($uri);
        
        // Assert
        $expectedArray = array(
            "Resource/id" => 1
        );
        $this->assertEquals(count($expectedArray), count($params));
        $this->assertSame($expectedArray, $params);
    }

    /** @test **/
    public function getParameters_DoesNotReturnsNamedMatches_WhichAreNotParameters()
    {
        // Arrange
        $uri = "/resource/1";
        $regex = "#^/resource/(?P<id>\d+)/(?P<extra>.*)$#";
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleIntParam());
        $matchableUri = new \Nap\Uri\MatchableUri($regex, $resource);

        // Act
        $params = $matchableUri->getParameters($uri);

        // Assert
        $expectedArray = array(
            "Resource/id" => 1
        );
        $this->assertNotSame($expectedArray, $params);
        $this->assertArrayNotHasKey("extra", $params);
    }

    /** @test **/
    public function getParameters_PrefixesMatchedParameter_WithResourceName()
    {
        // Arrange
        $uri = "/resource/1";
        $regex = "#^/resource/(?P<id>\d+)$#";
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleIntParam());
        $matchableUri = new \Nap\Uri\MatchableUri($regex, $resource);

        // Act
        $params = $matchableUri->getParameters($uri);

        // Assert
        $this->assertArrayHasKey("Resource/id", $params);
    }

    /** @test **/
    public function getParameters_WhenMatchingParentParameter_PrefixesParameter_WithParentName()
    {
        // Arrange
        $uri = "/resource/1/child/1";
        $regex = "#^/resource/(?P<id1>\d+)/child/(?P<id2>\d+)$#";

        $child = new \Nap\Resource\Resource("Child", "/child", new Stub_ParamScheme_SingleIntParam("id2"));
        $parent = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleIntParam("id1"), array(
            $child
        ));
        $matchableUri = new \Nap\Uri\MatchableUri($regex, $child);

        // Act
        $params = $matchableUri->getParameters($uri);

        // Assert
        $this->assertArrayHasKey("Resource/id", $params);
        $this->assertArrayHasKey("Child/id", $params);
    }
} 