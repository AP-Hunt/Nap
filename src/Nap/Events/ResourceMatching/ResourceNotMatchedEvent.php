<?php
namespace Nap\Events\ResourceMatching;

use Symfony\Component\EventDispatcher\Event;

class ResourceNotMatchedEvent extends Event
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