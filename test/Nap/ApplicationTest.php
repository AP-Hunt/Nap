<?php

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    private $matcher;
    private $resolver;
    private $builder;

    private $request;

    public function setUp()
    {
        $this->matcher = $this->getMockBuilder("\Nap\Resource\ResourceMatcher")
                    ->disableOriginalConstructor()->getMock();

        $this->resolver = $this->getMock("\Nap\Controller\ControllerResolutionStrategy");
        $this->builder = $this->getMock("\Nap\Controller\ControllerBuilderStrategy");

        $this->app = new \Nap\Application($this->matcher, $this->resolver, $this->builder);
        $this->app->setResources(
            array(
                new \Nap\Resource\Resource("MyResource", "/my/resource")
            )
        );

        $this->request = \Symfony\Component\HttpFoundation\Request::create(
            "/my/resource",
            "GET"
        );
    }

    /** @test **/
    public function StartDeconstructsUriAndMatchesItToAResource()
    {
        // Arrange
        $uri = "/my/resource?query=string";
        $expectedPath = "/my/resource";
        $this->matcher->expects($this->once())
                ->method("match")
                ->with($expectedPath)
                ->will($this->returnValue(new \Nap\Resource\Resource("MyResource", "")));

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
        $resource = new \Nap\Resource\Resource("Resource", "");
        $uri = "/my/resource?query=string";
        $expectedPath = "/my/resource";

        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($resource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($resource);

        $this->expectBuilderReturnsValidController();

        // Act
        $this->app->start($this->request);
    }

    /** @test **/
    public function StartBuildsResolvedController()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "");
        $controllerPath = "\My\ResourceController.php";
        $expectedPath = "/my/resource";

        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($resource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($resource)
            ->will($this->returnValue($controllerPath));

        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($controllerPath)
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
        $resource = new \Nap\Resource\Resource("Resource", "");
        $controllerPath = "\My\ResourceController.php";
        $uri = "/my/resource?query=string";
        $expectedPath = "/my/resource";

        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($resource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($resource)
            ->will($this->returnValue($controllerPath));

        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($controllerPath)
            ->will($this->returnValue(new StdClass()));

        // Act
        $this->app->start($this->request);
    }

    /** @test **/
    public function StartDispatchesMethodOnController()
    {
        // Arrange
        $resource = new \Nap\Resource\Resource("Resource", "");
        $controllerPath = "\My\ResourceController.php";
        $expectedPath = "/my/resource";

        $this->matcher->expects($this->once())
            ->method("match")
            ->with($expectedPath)
            ->will($this->returnValue($resource));

        $this->resolver->expects($this->once())
            ->method("resolve")
            ->with($resource)
            ->will($this->returnValue($controllerPath));

        $controller = $this->getMock("\Nap\Controller\NapControllerInterface");
        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($controllerPath)
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