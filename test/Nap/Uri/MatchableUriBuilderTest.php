<?php

class MatchableUriBuilderTest extends PHPUnit_Framework_TestCase
{
    /** @var \Nap\Uri\MatchableUriBuilder */
    private $builder;

    public function setUp()
    {
        $this->builder = new \Nap\Uri\MatchableUriBuilder();
    }

    public function tearDown()
    {
        $this->builder = null;
    }

    /** @test **/
    public function resourceWithNoParameters_NoChildren_GeneratesOneUri()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource");
        $expectedUriRegexs = array(
            "#^/resource$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithOneRequiredParameter_NoChildren_GeneratesOneUri()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleRequiredParam());
        $expectedUriRegexs = array(
            "#^/resource/(?P<id>\d+)$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithOneOptionalParameter_NoChildren_GeneratesTwoUris()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleOptionalParam());
        $expectedUriRegexs = array(
            "#^/resource$#",
            "#^/resource/(?P<id>\d+)$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithNoParameters_OneChildWithNoParams_GeneratesTwoUris()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", null, array(
            new \Nap\Resource\Resource("Child", "/child")
        ));
        $expectedUriRegexs = array(
            "#^/resource$#",
            "#^/resource/child$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithNoParameters_OneChildWithOneRequiredParam_GeneratesTwoUris()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", null, array(
            new \Nap\Resource\Resource("Child", "/child", new Stub_ParamScheme_SingleRequiredParam())
        ));
        $expectedUriRegexs = array(
            "#^/resource$#",
            "#^/resource/child/(?P<id>\d+)$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithNoParameters_OneChildWithOneOptionalParam_GeneratesThreeUris()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", null, array(
            new \Nap\Resource\Resource("Child", "/child", new Stub_ParamScheme_SingleOptionalParam())
        ));
        $expectedUriRegexs = array(
            "#^/resource$#",
            "#^/resource/child$#",
            "#^/resource/child/(?P<id>\d+)$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithOneRequiredParameter_OneChildWithNoParams_GeneratesTwoUris()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleRequiredParam(), array(
            new \Nap\Resource\Resource("Child", "/child")
        ));
        $expectedUriRegexs = array(
            "#^/resource/(?P<id>\d+)$#",
            "#^/resource/(?P<id>\d+)/child$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function resourceWithOneOptionalParameter_OneChildWithNoParams_GeneratesThreeUris()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stub_ParamScheme_SingleOptionalParam(), array(
            new \Nap\Resource\Resource("Child", "/child")
        ));
        $expectedUriRegexs = array(
            "#^/resource$#",
            "#^/resource/(?P<id>\d+)$#",
            "#^/resource/child$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    private function assertGeneratedUris(\Nap\Resource\Resource $resource, array $expectedUriRegexs)
    {
        // Act
        $uris = $this->builder->buildUrisForResource($resource);

        // Assert
        $countExpected = count($expectedUriRegexs);
        $countActual = count($uris);
        $this->assertEquals($countExpected, $countActual, "Expected ".$countExpected. " URIs but got ".$countActual);
        for ($i = 0; $i <= count($expectedUriRegexs) - 1; $i++) {
            $regex = $expectedUriRegexs[$i];
            $uri = $uris[$i];

            $this->assertEquals($regex, $uri->getUriRegex(), "Expected ".$regex." but got " . $uri->getUriRegex());
        }
    }
}

class Stub_Param implements \Nap\Resource\Parameter\ParameterInterface
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $required;
    /**
     * @var
     */
    private $matchingExpression;

    public function __construct($name, $required, $matchingExpression)
    {

        $this->name = $name;
        $this->required = $required;
        $this->matchingExpression = $matchingExpression;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        return $this->matchingExpression;
    }

    /**
     * Whether the parameter is mandatory within the route
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Converts the matched value in to the intended data type
     *
     * @param   string $value
     * @return  mixed
     */
    public function convertValue($value)
    {
        // TODO: Implement convertValue() method.
    }
}


class Stub_ParamScheme_SingleRequiredParam implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new Stub_Param("id", true, "\d+")
        );
    }
}

class Stub_ParamScheme_SingleOptionalParam implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new Stub_Param("id", false, "\d+")
        );
    }
}