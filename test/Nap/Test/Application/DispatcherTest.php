<?php
namespace Nap\Test\Application;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    private $controller;
    private $request;

    /** @var \Nap\Application\Dispatcher */
    private $dispatcher;

    public function setUp()
    {
        $this->controller = $this->getMock("\Nap\Controller\NapControllerInterface");
        $this->request = $this->getMockBuilder("\Symfony\Component\HttpFoundation\Request")
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->dispatcher = new \Nap\Application\Dispatcher();
    }

    public function tearDown()
    {
        $this->dispatcher = null;
        $this->request = null;
        $this->controller = null;
    }

    /** @test **/
    public function CallsIndexOnController_WhenMethodIsGet_AndZeroParams()
    {
        // Arrange
        $params = array();
        $this->expectRequestMethod($this->request, "GET");
        $this->expectControllerAction($this->controller, "index");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );
    }

    /** @test **/
    public function CallsGetOnController_WhenMethodIsGet_AndMoreThanZeroParams()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectRequestMethod($this->request, "GET");
        $this->expectControllerAction($this->controller, "get");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );
    }

    /** @test **/
    public function CallsPostOnController_WhenMethodIsPost()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectRequestMethod($this->request, "POST");
        $this->expectControllerAction($this->controller, "post");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );
    }

    /** @test **/
    public function CallsPutOnController_WhenMethodIsPut()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectRequestMethod($this->request, "PUT");
        $this->expectControllerAction($this->controller, "put");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );
    }

    /** @test **/
    public function CallsDeleteOnController_WhenMethodIsDelete()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectRequestMethod($this->request, "DELETE");
        $this->expectControllerAction($this->controller, "delete");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );
    }

    /** @test **/
    public function CallsOptionsOnController_WhenMethodIsOptions()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectRequestMethod($this->request, "OPTIONS");
        $this->expectControllerAction($this->controller, "options");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );
    }

    /** @test **/
    public function ReturnsResultFromControllerMethod()
    {
        // Arrange
        $params = array("param1" => "value");
        $mockResult = $this->getMock("Nap\Controller\ResultInterface");
        $this->expectRequestMethod($this->request, "GET");
        $this->expectControllerAction($this->controller, "get", $mockResult);

        // Act
        $result = $this->dispatcher->dispatchMethod(
            $this->controller,
            $this->request,
            $params
        );

        // Assert
        $this->assertSame($mockResult, $result);
    }

    private function expectRequestMethod($requestMock, $method)
    {
        $requestMock->expects($this->once())
                ->method("getMethod")
                ->will($this->returnValue($method));
    }

    private function expectControllerAction($controllerMock, $action, $result = null)
    {
        $controllerMock->expects($this->once())
                ->method($action)
                ->will($this->returnValue($result));
    }
}