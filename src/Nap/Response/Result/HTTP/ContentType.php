<?php
namespace Nap\Response\Result\HTTP;

use Nap\Response\HeaderResultsInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentType implements HeaderResultsInterface
{
    /**
     * @var \Nap\Response\HeaderResultsInterface
     */
    private $headers;
    /**
     * @var
     */
    private $mimeType;

    public function __construct(HeaderResultsInterface $headers, $mimeType)
    {
        $this->headers = $headers;
        $this->mimeType = $mimeType;
    }

    /**
     * Sets headers (including cookies and cache) on the response
     *
     * @param   Response $response
     * @return  void
     */
    public function setHeadersOnResponse(Response $response)
    {
        $response->headers->set("Content-Type", $this->mimeType);
        $this->headers->setHeadersOnResponse($response);
    }
}