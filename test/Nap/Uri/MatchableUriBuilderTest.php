<?php

class MatchableUriBuilderTest extends PHPUnit_Framework_TestCase
{
    /** @test **/
    public function WhenRootResourceHasNoChildren_GeneratesOneUri()
    {
        // Arrange
        $root = new \Nap\Resource\Resource("MyResource", "/my/resource", null);
        $expectedRegex = "#^/my/resource$#";
        $expectedCount = 1;

        $builder  = new \Nap\Uri\MatchableUriBuilder();

        // Act
        $uris = $builder->buildUrisForResource($root);
        
        // Assert
        $this->assertEquals($expectedCount, count($uris));
        $this->assertEquals($expectedRegex, $uris[0]->getUriRegex());
    }

    /** @test **/
    public function WhenRootResourceHasOneChild_GeneratesTwoUris()
    {
        // Arrange
        $root = new \Nap\Resource\Resource("MyResource", "/my/resource", null, array(
            new \Nap\Resource\Resource("Child", "/child", null)
        ));
        $expectedRegexs = array(
            "#^/my/resource$#",
            "#^/my/resource/child$#"
        );
        $expectedCount = 2;

        $builder  = new \Nap\Uri\MatchableUriBuilder();

        // Act
        $uris = $builder->buildUrisForResource($root);

        // Assert
        $this->assertEquals($expectedCount, count($uris));
        foreach($uris as $i => $u){
            $this->assertEquals($expectedRegexs[$i], $u->getUriRegex());
        }
    }

    /** @test **/
    public function WhenRootResourceHasOneGrandChild_GeneratesThreeUris()
    {
        // Arrange
        $root = new \Nap\Resource\Resource("MyResource", "/my/resource", null, array(
            new \Nap\Resource\Resource("Child", "/child", null, array(
                new \Nap\Resource\Resource("Grandchild", "/grandchild", null)
            ))
        ));
        $expectedRegexs = array(
            "#^/my/resource$#",
            "#^/my/resource/child$#",
            "#^/my/resource/child/grandchild$#"
        );
        $expectedCount = 3;

        $builder  = new \Nap\Uri\MatchableUriBuilder();

        // Act
        $uris = $builder->buildUrisForResource($root);

        // Assert
        $this->assertEquals($expectedCount, count($uris));
        foreach($uris as $i => $u){
            $this->assertEquals($expectedRegexs[$i], $u->getUriRegex());
        }
    }

    /** @test **/
    public function WhenRootResourceHasTwoChildren_GeneratesThreeUris()
    {
        // Arrange
        $root = new \Nap\Resource\Resource("MyResource", "/my/resource", null, array(
            new \Nap\Resource\Resource("Child", "/child", null),
            new \Nap\Resource\Resource("Sibling", "/sibling", null)
        ));
        $expectedRegexs = array(
            "#^/my/resource$#",
            "#^/my/resource/child$#",
            "#^/my/resource/sibling$#"
        );
        $expectedCount = 3;

        $builder  = new \Nap\Uri\MatchableUriBuilder();

        // Act
        $uris = $builder->buildUrisForResource($root);

        // Assert
        $this->assertEquals($expectedCount, count($uris));
        foreach($uris as $i => $u){
            $this->assertEquals($expectedRegexs[$i], $u->getUriRegex());
        }
    }

    /** @test */
    public function WhenResourceHasNoParameterScheme_AndNoChildren_GeneratesOneUri()
    {
        // Arrange
        $root = new \Nap\Resource\Resource("MyResource", "/my/resource", null);
        $expectedRegex = "#^/my/resource$#";
        $expectedCount = 1;

        $builder  = new \Nap\Uri\MatchableUriBuilder();

        // Act
        $uris = $builder->buildUrisForResource($root);

        // Assert
        $this->assertEquals($expectedCount, count($uris));
        $this->assertEquals($expectedRegex, $uris[0]->getUriRegex());
    }

    /** @test **/
    public function WhenResourceHasParameterScheme_WithSingleParameter_AndNoChildren_GeneratesTwoUris()
    {
        // Arrange
        $paramScheme = new Stub_ParamScheme();
        $root = new \Nap\Resource\Resource("MyResource", "/my/resource", $paramScheme);
        $expectedRegexs = array(
            "#^/my/resource$#",
            "#^/my/resource/(?P<Stub>\d+)$#"
        );
        $expectedCount = 2;

        $builder  = new \Nap\Uri\MatchableUriBuilder();

        // Act
        $uris = $builder->buildUrisForResource($root);

        // Assert
        $this->assertEquals($expectedCount, count($uris));
        foreach($uris as $i => $u){
            $this->assertEquals($expectedRegexs[$i], $u->getUriRegex());
        }
    }
}

class Stub_ParamScheme implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new Stub_IntParameter()
        );
    }
}

class Stub_IntParameter implements \Nap\Resource\Parameter\ParameterInterface
{

    /**
     * @return string
     */
    public function getName()
    {
        return "Stub";
    }

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        return "\d+";
    }

    /**
     * Converts the matched value in to the intended data type
     *
     * @param   string $value
     * @return  mixed
     */
    public function convertValue($value)
    {
        return $value;
    }
}