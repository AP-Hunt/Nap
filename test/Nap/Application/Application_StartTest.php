<?php

require_once __DIR__."/ApplicationTestBase.php";

class Application_StartTest extends ApplicationTestBase
{
    /** @test **/
    public function StartDeconstructsUriAndMatchesItToAResource()
    {
        // Arrange
        $expectedPath = "/my/resource";
        $matchedResource = new \Nap\Resource\MatchedResource(new \Nap\Resource\Resource("MyResource", "", null), array());
        $this->matcher->expects($this->once())
                ->method("match")
                ->with($expectedPath)
                ->will($this->returnValue($matchedResource));

        $this->expectBuilderReturnsValidController();

        // Act
        $this->app->start($this->request);
    }

    /**
     * @test
     * @expectedException \Nap\Resource\NoMatchingResourceException
     **/
    public function StartThrowsNoMatchingResourceExceptionWhenNoResourceMatches()
    {
        $request = \Symfony\Component\HttpFoundation\Request::create(
            "/my/resource/is/not/found",
            "GET",
            array("query" => "string")
        );
        $expectedPath = "/my/resource/is/not/found";
        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue(null));

        // Act
        $this->app->start($request);
    }

    /** @test **/
    public function StartResolvesMatchedResource()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "", null);
        $uri = "/my/resource?query=string";
        $expectedPath = "/my/resource";

        $matchedResource = new \Nap\Resource\MatchedResource($resource, array());
        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($matchedResource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($this->isType("string"), $resource);

        $this->expectBuilderReturnsValidController();

        // Act
        $this->app->start($this->request);
    }

    /** @test **/
    public function StartBuildsResolvedController()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "", null);
        $controllerFQN = "\My\ResourceController";
        $expectedPath = "/my/resource";

        $matchedResource = new \Nap\Resource\MatchedResource($resource, array());
        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($matchedResource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($this->isType("string"), $resource)
            ->will($this->returnValue($controllerFQN));

        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($controllerFQN)
            ->will($this->returnValue($this->getMock("\Nap\Controller\NapControllerInterface")));

        // Act
        $this->app->start($this->request);
    }

    /**
     * @test
     * @expectedException \Nap\Controller\InvalidControllerException
     **/
    public function StartThrowsInvalidControllerExceptionWhenControllerDoesNotImplementInterface()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "", null);
        $controllerFQN = "\My\ResourceController";
        $expectedPath = "/my/resource";

        $matchedResource = new \Nap\Resource\MatchedResource($resource, array());
        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($matchedResource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($this->isType("string"), $resource)
            ->will($this->returnValue($controllerFQN));

        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($controllerFQN)
            ->will($this->returnValue(new StdClass()));

        // Act
        $this->app->start($this->request);
    }

    /** @test **/
    public function StartDispatchesMethodOnController()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "", null);
        $controllerFQN = "\My\ResourceController";
        $expectedPath = "/my/resource";

        $matchedResource = new \Nap\Resource\MatchedResource($resource, array("id" => 1));
        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($matchedResource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($this->isType("string"), $resource)
            ->will($this->returnValue($controllerFQN));

        $controller = $this->getMock("\Nap\Controller\NapControllerInterface");
        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($controllerFQN)
            ->will($this->returnValue($controller));

        $controller->expects($this->once())
                ->method(strtolower($this->request->getMethod()))
                ->with($this->request);

        // Act
        $this->app->start($this->request);
    }

    private function expectBuilderReturnsValidController()
    {
        $this->builder->expects($this->once())
            ->method("buildController")
            ->will($this->returnValue($this->getMock("\Nap\Controller\NapControllerInterface")));
    }
}