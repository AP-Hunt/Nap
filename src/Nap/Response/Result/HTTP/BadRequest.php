<?php
namespace Nap\Response\Result\HTTP;

use Nap\Response\HeaderResultsInterface;
use Symfony\Component\HttpFoundation\Response;

class BadRequest implements HeaderResultsInterface
{
    /**
     * @var \Nap\Response\HeaderResultsInterface
     */
    private $headers;

    public function __construct(HeaderResultsInterface $headers = null)
    {
        $this->headers = $headers;
    }

    /**
     * Sets headers (including cookies and cache) on the response
     *
     * @param   Response $response
     * @return  void
     */
    public function setHeadersOnResponse(Response $response)
    {
        $response->setStatusCode(400);
        if($this->headers != null) {
            $this->headers->setHeadersOnResponse($response);
        }
    }
}