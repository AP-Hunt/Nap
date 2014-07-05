<?php

namespace Nap\Test\Application;

use Nap\Application\ActionDispatcher;
use Nap\Events\ActionDispatcherEvents;
use Nap\Resource\MatchedResource;
use Nap\Resource\Resource;
use Symfony\Component\HttpFoundation\Request;

class ActionDispatcherTest extends \PHPUnit_Framework_TestCase
{
    private $eventDispatcher;
    private $builder;
    private $contentNegotiator;

    /** @var \Nap\Metadata\ControllerMetadataProvider */
    private $controllerMetadataProvider;

    /** @var ActionDispatcher */
    private $actionDispatcher;

    public function setUp()
    {
        $this->eventDispatcher = $this->getEventDispatcherMock();
        $this->builder = $this->getMock("\Nap\Controller\ControllerBuilderStrategy");
        $this->controllerMetadataProvider = new \Nap\Test\Application\Stubs\ControllerMetadataProvider();
        $this->contentNegotiator = $this->getMock("\Nap\Application\ContentNegotiatorInterface");

        $this->actionDispatcher = new ActionDispatcher(
            $this->eventDispatcher,
            $this->builder,
            $this->controllerMetadataProvider,
            $this->contentNegotiator
        );
    }

    /**
     * @test
     * @expectedException \Nap\Controller\InvalidControllerException
     **/
    public function WhenBuiltControllerIsNotANapController_RaisesEvent()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array()
        );

        $this->builder->expects($this->once())
                ->method("buildController")
                ->with($matchedResource->getResource())
                ->will($this->returnValue(new \StdClass()));

        $request = $this->getMock("\Symfony\Component\HttpFoundation\Request");

        // Act
        $this->actionDispatcher->dispatch($request, $matchedResource);
    }

    /**
     * @test
     * @expectedException \Nap\Metadata\MissingDefaultResultTypeException
     **/
    public function whenRequestDoesNotContainAcceptHeader_AndControllerMissingDefaultType_ThrowsException()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array()
        );

        $this->expectControllerFromMatchedResource($matchedResource);

        $request = Request::create("/uri","GET");
        $request->headers->set("Accept", null);

        // Act
        $this->actionDispatcher->dispatch($request, $matchedResource);
    }

    /** @test **/
    public function whenRequestHasAcceptHeader_AndTypesInMetadataDoNotMatchThoseInHeader_RaisesEvent()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array()
        );

        $this->expectControllerFromMatchedResource($matchedResource);

        $request = Request::create("/uri", "GET");
        $request->headers->set("Accept", "application/unmatchable");

        $this->contentNegotiator->expects($this->once())
                ->method("getBestMatch")
                ->will($this->returnValue(null));

        $this->expectEvent(
            $this->eventDispatcher,
            ActionDispatcherEvents::NO_APPROPRIATE_RESPONSE,
            "\Nap\Events\ActionDispatcher\NoAppropriateResponseEvent"
        );

        // Act
        $this->actionDispatcher->dispatch($request, $matchedResource);
    }

    /** @test **/
    public function callsIndexOnController_WhenRequestIsGet_AndHasNoParameters()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array()
        );

        $this->expectControllerCall("index");

        $reqquest = Request::create("/uri", "GET");
        $reqquest->headers->set("Accept", "application/index");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/index"));

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function callsGetOnController_WhenRequestIsGet_AndHasSomeParameters()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("get");

        $reqquest = Request::create("/uri", "GET");
        $reqquest->headers->set("Accept", "application/get");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/get"));

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function callsPostOnController_WhenRequestIsPost()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("post");

        $reqquest = Request::create("/uri", "POST");
        $reqquest->headers->set("Accept", "application/post");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/post"));

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function callsPutOnController_WhenRequestIsPut()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("put");

        $reqquest = Request::create("/uri", "PUT");
        $reqquest->headers->set("Accept", "application/put");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/put"));

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function callsDeleteOnController_WhenRequestIsDelete()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("delete");

        $reqquest = Request::create("/uri", "DELETE");
        $reqquest->headers->set("Accept", "application/delete");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/delete"));

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function callsOptionsOnController_WhenRequestIsOptions()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("options");

        $reqquest = Request::create("/uri", "OPTIONS");
        $reqquest->headers->set("Accept", "application/options");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/options"));

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function raisesControllerExecutedEvent()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("get");

        $reqquest = Request::create("/uri", "GET");
        $reqquest->headers->set("Accept", "application/get");

        $this->contentNegotiator->expects($this->once())
            ->method("getBestMatch")
            ->will($this->returnValue("application/get"));

        $this->expectEvent(
            $this->eventDispatcher,
            ActionDispatcherEvents::CONTROLLER_EXECUTED,
            "\Nap\Events\ActionDispatcher\ControllerExecutedEvent"
        );

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    /** @test **/
    public function whenRequestHasNoAcceptHeader_UsesDefaultMimeTypeInEventArgs()
    {
        // Arrange
        $matchedResource = new MatchedResource(
            new Resource("Test", "/test"),
            array(
                array("Test/Id" => 1)
            )
        );

        $this->expectControllerCall("get");

        $reqquest = Request::create("/uri", "GET");
        $reqquest->headers->set("Accept", null);

        $this->expectEvent(
            $this->eventDispatcher,
            ActionDispatcherEvents::CONTROLLER_EXECUTED,
            "\Nap\Events\ActionDispatcher\ControllerExecutedEvent"
        )
                ->will($this->returnCallback(
                    function(
                        $eventName,
                        \Nap\Events\ActionDispatcher\ControllerExecutedEvent $evtArgs)
                    {
                        $this->assertEquals("application/defaultGet", $evtArgs->getMimeType());
                    }
                )
            );

        // Act
        $this->actionDispatcher->dispatch($reqquest, $matchedResource);
    }

    private function getEventDispatcherMock()
    {
        return $this->getMock("\Symfony\Component\EventDispatcher\EventDispatcherInterface");
    }

    private function expectEvent($eventDispatcher, $eventName, $eventType)
    {
        return $eventDispatcher->expects($this->once())
                ->method("dispatch")
                ->with($eventName, $this->isInstanceOf($eventType));
    }

    /**
     * @param $matchedResource
     */
    private function expectControllerFromMatchedResource($matchedResource)
    {
        $this->builder->expects($this->once())
            ->method("buildController")
            ->with($matchedResource->getResource())
            ->will($this->returnValue($this->getMock("\Nap\Controller\NapControllerInterface")));
    }

    private function expectControllerCall($method)
    {
        $controller = $this->getMock("\Nap\Controller\NapControllerInterface");
        $this->builder->expects($this->once())
                ->method("buildController")
                ->will($this->returnValue($controller));

        $controller->expects($this->once())
                ->method($method)
                ->will($this->returnValue($this->getMock("\Nap\Controller\ResultInterface")));
    }
} 