<?php
namespace Nap\Uri;

class MatchableUri
{
    /** @var string $uriRegex */
    private $uriRegex;

    /** @var Resource $resource*/
    private $resource;

    public function __construct($uriRegex, \Nap\Resource\Resource $resource)
    {
        $this->uriRegex = $uriRegex;
        $this->resource = $resource;
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return string
     */
    public function getUriRegex()
    {
        return $this->uriRegex;
    }

    /**
     * @param   string $uri Uri to match against
     * @return  bool
     */
    public function matches($uri)
    {
        return preg_match($this->uriRegex, $uri);
    }

    /**
     * Gets the values of any parameters defined in the resource's parameter scheme
     *
     * @return mixed[]
     */
    public function getParameters()
    {
        return array();
    }
} 