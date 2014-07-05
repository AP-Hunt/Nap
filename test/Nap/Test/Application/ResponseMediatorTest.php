<?php
namespace Nap\Test\Application;


use Nap\Response\Result\Data;
use Nap\Events\ActionDispatcher\NoAppropriateResponseEvent;
use Nap\Events\ResourceMatching\ResourceNotMatchedEvent;
use Nap\Serialisation\SerialiserRegistry;

class ResponseMediatorTest extends \PHPUnit_Framework_TestCase
{
    private $responder;

    /** @var SerialiserRegistry */
    private $serialiserRegistry;

    /** @var \Nap\Application\ResponseMediator */
    private $mediator;

    public function setUp()
    {
        $this->responder = $this->getMockBuilder("\Nap\Response\Responder")
                            ->disableOriginalConstructor()->getMock();

        $this->serialiserRegistry = new SerialiserRegistry();

        $this->mediator = new \Nap\Application\ResponseMediator(
            $this->responder,
            $this->serialiserRegistry
        );
    }

    /** @test **/
    public function notFound_WritesANotFoundResponseToResponse()
    {
        // Arrange
        $this->responder->expects($this->once())
                ->method("writeResponse")
                ->with(
                    $this->isInstanceOf("\Nap\Response\Result\HTTP\NotFound"),
                    ""
                );

        $notMatchedEvent = new ResourceNotMatchedEvent("/uri");

        // Act
        $this->mediator->notFound($notMatchedEvent);
    }

    /** @test **/
    public function noAppropriateResponse_WritesANotAcceptableResponseToResponse()
    {
        // Arrange
        $this->responder->expects($this->once())
            ->method("writeResponse")
            ->with(
                $this->isInstanceOf("\Nap\Response\Result\HTTP\NotAcceptable"),
                ""
            );

        $noAppropriateResponseEvent = new NoAppropriateResponseEvent();

        // Act
        $this->mediator->noAppropriateResponse($noAppropriateResponseEvent);
    }
    
    /** @test **/
    public function validResponse_WritesSerialisedFormAndOKHeaders()
    {
        // Arrange
        $this->serialiserRegistry->registerSerialiser(
            "application/test",
            new Stubs\Serialiser()
        );

        $event = new \Nap\Events\ActionDispatcher\ControllerExecutedEvent(
            new Data(array()),
            "application/test"
        );

        $this->responder->expects($this->once())
                ->method("writeResponse")
                ->with(
                    $this->isInstanceOf("\Nap\Response\Result\HTTP\OK"),
                    "serialised" // Value from stub
                );

        // Act
        $this->mediator->validResponse($event);
    }

    /**
     * @test
     * @expectedException \Nap\Application\NoSerialiserConfiguredException
     **/
    public function validResponse_ThrowsExceptionWhenNoSerialiserFound()
    {
        // Arrange
        $event = new \Nap\Events\ActionDispatcher\ControllerExecutedEvent(
            new Data(array()),
            "application/test"
        );

        // Act
        $this->mediator->validResponse($event);
    }
}