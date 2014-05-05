<?php
namespace Nap\Resource;

class MatchedResource
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var mixed[]
     */
    private $parameters;

    public function __construct(\Nap\Resource\Resource $resource, array $parameters)
    {
        $this->resource = $resource;
        $this->parameters = $parameters;
    }
} 