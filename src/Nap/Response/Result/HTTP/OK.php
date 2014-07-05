<?php
namespace Nap\Response\Result\HTTP;

use Nap\Response\HeaderResultsInterface;
use Nap\Response\ResultBase;
use Symfony\Component\HttpFoundation\Response;

class OK implements HeaderResultsInterface
{
    /**
     * @var \Nap\Response\HeaderResultsInterface
     */
    private $headers;

    public function __construct(HeaderResultsInterface $headers)
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
        $response->setStatusCode(200);

        if($this->headers != null)
        {
            $this->headers->setHeadersOnResponse($response);
        }
    }
}