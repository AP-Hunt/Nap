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

        $this->assertEquals($expectedArray, $actualArray);
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
            "id " => 1
        );
        $this->assertEquals(count($expectedArray), count($params));
        $this->assertTrue(array_diff($expectedArray, $params) == array());
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
            "id" => 1
        );
        $this->assertNotEquals($expectedArray, $params);
        $this->assertArrayNotHasKey("exttra", $params);
    }
} 