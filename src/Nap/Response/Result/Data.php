<?php
namespace Nap\Response\Result;


use Nap\Response\ResultBase;
use Symfony\Component\HttpFoundation\Response;

class Data extends ResultBase
{

    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gets response data
     *
     * @return  array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets headers (including cookies and cache) on the response
     *
     * @param   Response $response
     * @return  void
     */
    public function setHeadersOnResponse(Response $response)
    {
        // Does nothing
    }
}