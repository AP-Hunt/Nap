<?php

namespace Nap\Response\Result\HTTP;

use Nap\Response\HeaderResultsInterface;
use Symfony\Component\HttpFoundation\Response;

class NotAcceptable implements HeaderResultsInterface
{
    /**
     * @var \Nap\Response\HeaderResultsInterface
     */
    private $headerResults;

    public function __construct(HeaderResultsInterface $headerResults = null)
    {
        $this->headerResults = $headerResults;
    }
    /**
     * Sets headers (including cookies and cache) on the response
     *
     * @param   Response $response
     * @return  void
     */
    public function setHeadersOnResponse(Response $response)
    {
        $response->setStatusCode(406);
        if($this->headerResults != null)
        {
            $this->headerResults->setHeadersOnResponse($response);
        }
    }
}