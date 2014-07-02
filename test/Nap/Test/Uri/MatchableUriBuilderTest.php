<?php
namespace Nap\Test\Uri;

use \Nap\Test\Uri\Stubs;

class MatchableUriBuilderRewriteTest extends \PHPUnit_Framework_TestCase
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
    public function ResourceWithNoChildren_GeneratesMatchableUriReferringToItself()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme\SingleSelfOptionalParam());
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id>\d+))?/?$#" => $resource
        );

        // Act
        $this->assertGeneratedUrisAndResources($resource, $expectedUriRegexs);
    }

    /** @test */
    public function ResourceWithSingleSelfOptionalParameter_GeneratesUriWithOneOptionalPart()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme\SingleSelfOptionalParam());
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id>\d+))?/?$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function ResourceWithSingleSelfRequiredParameter_GeneratesUriWithOneRequiredPart()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme\SingleSelfRequiredParam());
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id>\d+))/?$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test * */
    public function ResourceWithOneSelfRequired_AndOneSelfOptionalParam_GeneratesUriWithRequiredThenOptionalPart()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme(
                array(
                    new Stubs\Param("Required", true, false, "\d+", "id_1"),
                    new Stubs\Param("Optional", false, false, "\d+", "id_2")
                )
            ));
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id_1>\d+))(/(?P<id_2>\d+))?/?$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function ResourceWithOneChild_GeneratesOneMatchableUriReferringToItself_AndOneReferringToTheChild()
    {
        // Arrange
        $child = new \Nap\Resource\Resource("Child", "/child", null);
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme(
            array(
                new Stubs\Param("Id", false, true, "\d+", "id")
            )
        ), array($child));
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id>\d+))?/?$#" => $resource,
            "#^/resource(/(?P<id>\d+))/child/?$#" => $child
        );

        // Act
        $this->assertGeneratedUrisAndResources($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function ResourceWithOneChild_AndOneChildRequiredParameter_GeneratesOwnUri_AndChildUriWithRequiredParameter()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme(
            array(
                new Stubs\Param("Id", false, true, "\d+", "id")
            )
        ), array(
            new \Nap\Resource\Resource("Child", "/child", null)
        ));
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id>\d+))?/?$#",
            "#^/resource(/(?P<id>\d+))/child/?$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function ResourceWithOneChild_AndOneChildOptionalParameter_GeneratesOwnUri_AndChildUriWithOptionalParameter()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme(
            array(
                new Stubs\Param("Id", false, false, "\d+", "id")
            )
        ), array(
            new \Nap\Resource\Resource("Child", "/child", null)
        ));
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id>\d+))?/?$#",
            "#^/resource(/(?P<id>\d+))?/child/?$#"
        );

        // Act
        $this->assertGeneratedUris($resource, $expectedUriRegexs);
    }

    /** @test **/
    public function ResourceWithOneChild_AndOneChildOptionalParameterAndOneChildRequiredParameter_GeneratesOwnUri_AndChildUriWithRequiredThenOptionalParameter()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "/resource", new Stubs\ParamScheme(
            array(
                new Stubs\Param("Id", false, true, "\d+", "id_req"),
                new Stubs\Param("Id2", false, false, "\d+", "id_opt")
            )
        ), array(
            new \Nap\Resource\Resource("Child", "/child", null)
        ));
        $expectedUriRegexs = array(
            "#^/resource(/(?P<id_req>\d+))?(/(?P<id_opt>\d+))?/?$#",
            "#^/resource(/(?P<id_req>\d+))(/(?P<id_opt>\d+))?/child/?$#"
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

    private function assertGeneratedUrisAndResources($resource, $expectedUriRegexs)
    {
        // Act
        $uris = $this->builder->buildUrisForResource($resource);

        // Assert
        $countExpected = count($expectedUriRegexs);
        $countActual = count($uris);
        $this->assertEquals($countExpected, $countActual, "Expected ".$countExpected. " URIs but got ".$countActual);

        $i = 0;
        foreach($expectedUriRegexs as $regex => $res) {
            $uri = $uris[$i];

            $this->assertEquals($regex, $uri->getUriRegex(), "Expected ".$regex." but got " . $uri->getUriRegex());
            $this->assertEquals($res, $uri->getResource(),
                                    "Expected to refer to resource " . $res->getName() . "but referred to ".$uri->getResource()->getName());
            $i++;
        }
    }
}
