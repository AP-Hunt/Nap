<?php

class ApplicationTestBase extends \PHPUnit_Framework_TestCase
{
    protected $matcher;
    protected $resolver;
    protected $builder;
    protected $app;

    protected $request;

    public function setUp()
    {
        $this->matcher = $this->getMockBuilder("\Nap\Resource\ResourceMatcher")
            ->disableOriginalConstructor()->getMock();

        $this->resolver = $this->getMock("\Nap\Controller\ControllerResolutionStrategy");
        $this->builder = $this->getMock("\Nap\Controller\ControllerBuilderStrategy");

        $this->app = new \Nap\Application($this->matcher, $this->resolver, $this->builder);
        $this->app->setResources(
            array(
                new \Nap\Resource\Resource("MyResource", "/my/resource", null)
            )
        );

        $this->request = \Symfony\Component\HttpFoundation\Request::create(
            "/my/resource",
            "GET"
        );
    }

    public function tearDown()
    {
        $this->request = null;
        $this->app = null;
        $this->builder = null;
        $this->resolver = null;
        $this->matcher = null;
    }
} 