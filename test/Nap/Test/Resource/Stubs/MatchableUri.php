<?php
namespace Nap\Test\Resource\Stubs;


use mixed;

class MatchableUri extends \Nap\Uri\MatchableUri
{
    private $resource;

    public function __construct(\Nap\Resource\Resource $resource)
    {
        $this->resource = $resource;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getUriRegex()
    {
        return "#^.*$#";
    }

    public function matches($uri)
    {
        return true;
    }

    public function getParameters($uri)
    {
        return array();
    }

} 