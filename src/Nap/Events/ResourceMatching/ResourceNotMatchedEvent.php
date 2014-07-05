<?php
namespace Nap\Events\ResourceMatching;

use Nap\Events\ResultEvent;

class ResourceNotMatchedEvent extends ResultEvent
{
    /** @var string */
    private $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}