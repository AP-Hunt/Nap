<?php
namespace Nap\Response;


use Symfony\Component\HttpFoundation\Response;

interface HeaderResultsInterface
{
    /**
     * Sets headers (including cookies and cache) on the response
     *
     * @param   Response $response
     * @return  void
     */
    public function setHeadersOnResponse(Response $response);
}