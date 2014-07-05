<?php
namespace Nap\Response;


use Symfony\Component\HttpFoundation\Response;

class Responder
{
    /** @var \Symfony\Component\HttpFoundation\Response */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function writeResponse(HeaderResultsInterface $headers, $body)
    {
        $headers->setHeadersOnResponse($this->response);
        $this->response->setContent($body);
        $this->response->send();
    }
}