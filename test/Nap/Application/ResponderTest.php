<?php

class ResponderTest extends \PHPUnit_Framework_TestCase
{
    const MIME_TYPE = "application/json";

    private $request;
    private $negotiator;

    /** @var \Nap\Application\Responder */
    private $responder;

    public function setUp()
    {
        $this->request = \Symfony\Component\HttpFoundation\Request::create(
            "/test/uri",
            "GET",
            array(),
            array(),
            array(),
            array("CONTENT_TYPE" => self::MIME_TYPE),
            array()
        );
        $this->negotiator = $this->getMock("\Nap\Application\ContentNegotiatorInterface");
        $this->responder = new \Nap\Application\Responder();
    }

    /** @test **/
    public function usesSerializerFromContentNegotiatorResponse()
    {
        // Arrange
        $data = array();
        $result = $this->getMock("\Nap\Controller\ResultInterface");
        $result->expects($this->any())
                ->method("getData")
                ->will($this->returnValue($data));

        $serialiser = $this->getMock("\Nap\Serialisation\SerialiserInterface");
        $this->responder->registerSerialiser(
            self::MIME_TYPE,
            $serialiser

        );

        $serialiser->expects($this->once())
            ->method("serialise")
            ->with($data);

        $this->negotiator->expects($this->once())
                ->method("getBestMatch")
                ->will($this->returnValue(self::MIME_TYPE));


        // Act
        $this->responder->respond(
            $this->request,
            $this->negotiator,
            $result
        );
    }
}