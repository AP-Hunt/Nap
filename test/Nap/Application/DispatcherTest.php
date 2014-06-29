<?php


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
    public function DispatchMethod_CallsIndexOnController_WhenMethodIsIndex()
    {
        // Arrange
        $params = array();
        $this->expectControllerAction($this->controller, "index");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            "index",
            $this->request,
            $params
        );
    }

    /** @test **/
    public function DispatchMethod_CallsGetOnController_WhenMethodIsGet()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectControllerAction($this->controller, "get");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            "get",
            $this->request,
            $params
        );
    }

    /** @test **/
    public function DispatchMethod_CallsPostOnController_WhenMethodIsPost()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectControllerAction($this->controller, "post");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            "post",
            $this->request,
            $params
        );
    }

    /** @test **/
    public function DispatchMethod_CallsPutOnController_WhenMethodIsPut()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectControllerAction($this->controller, "put");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            "put",
            $this->request,
            $params
        );
    }

    /** @test **/
    public function DispatchMethod_CallsDeleteOnController_WhenMethodIsDelete()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectControllerAction($this->controller, "delete");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            "delete",
            $this->request,
            $params
        );
    }

    /** @test **/
    public function DispatchMethod_CallsOptionsOnController_WhenMethodIsOptions()
    {
        // Arrange
        $params = array("param1" => "value");
        $this->expectControllerAction($this->controller, "options");

        // Act
        $this->dispatcher->dispatchMethod(
            $this->controller,
            "options",
            $this->request,
            $params
        );
    }

    /** @test **/
    public function DispatchMethod_ReturnsResultFromControllerMethod()
    {
        // Arrange
        $params = array("param1" => "value");
        $mockResult = $this->getMock("Nap\Controller\ResultInterface");
        $this->expectControllerAction($this->controller, "get", $mockResult);

        // Act
        $result = $this->dispatcher->dispatchMethod(
            $this->controller,
            "get",
            $this->request,
            $params
        );

        // Assert
        $this->assertSame($mockResult, $result);
    }

    /** @test **/
    public function GetMethod_ReturnsIndex_WhenRequestIsGet_AndNumberParamsIsZero()
    {
        // Arrange
        $expected = "index";
        $this->expectRequestMethod($this->request, "get");
        $numParams = 0;

        // Act
        $method = $this->dispatcher->getMethod($this->request, $numParams);

        // Assert
        $this->assertEquals($expected, $method);
    }

    /** @test **/
    public function GetMethod_ReturnsGet_WhenRequestIsGet_AndNumberParamsMoreThanZero()
    {
        // Arrange
        $expected = "get";
        $this->expectRequestMethod($this->request, "get");
        $numParams = 1;

        // Act
        $method = $this->dispatcher->getMethod($this->request, $numParams);

        // Assert
        $this->assertEquals($expected, $method);
    }

    /** @test **/
    public function GetMethod_ReturnsPost_WhenRequestIsPost()
    {
        // Arrange
        $expected = "post";
        $this->expectRequestMethod($this->request, "post");
        $numParams = 1;

        // Act
        $method = $this->dispatcher->getMethod($this->request, $numParams);

        // Assert
        $this->assertEquals($expected, $method);
    }

    /** @test **/
    public function GetMethod_ReturnsPut_WhenRequestIsPut()
    {
        // Arrange
        $expected = "put";
        $this->expectRequestMethod($this->request, "put");
        $numParams = 1;

        // Act
        $method = $this->dispatcher->getMethod($this->request, $numParams);

        // Assert
        $this->assertEquals($expected, $method);
    }

    /** @test **/
    public function GetMethod_ReturnsDelete_WhenRequestIsDelete()
    {
        // Arrange
        $expected = "delete";
        $this->expectRequestMethod($this->request, "delete");
        $numParams = 1;

        // Act
        $method = $this->dispatcher->getMethod($this->request, $numParams);

        // Assert
        $this->assertEquals($expected, $method);
    }

    /** @test **/
    public function GetMethod_ReturnsOptions_WhenRequestIsOptions()
    {
        // Arrange
        $expected = "options";
        $this->expectRequestMethod($this->request, "options");
        $numParams = 1;

        // Act
        $method = $this->dispatcher->getMethod($this->request, $numParams);

        // Assert
        $this->assertEquals($expected, $method);
    }

    private function expectControllerAction($controllerMock, $action, $result = null)
    {
        $controllerMock->expects($this->once())
                ->method($action)
                ->will($this->returnValue($result));
    }

    private function expectRequestMethod($requestMock, $method)
    {
        $requestMock->expects($this->once())
            ->method("getMethod")
            ->will($this->returnValue($method));
    }
}