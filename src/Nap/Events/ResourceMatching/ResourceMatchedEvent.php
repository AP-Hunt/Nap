<?php
namespace Nap\Events\ResourceMatching;

use Nap\Resource\MatchedResource;
use Symfony\Component\EventDispatcher\Event;

class ResourceMatchedEvent extends Event
{
    /**
     * @var \Nap\Resource\Resource
     */
    private $foundResource;

    public function __construct(MatchedResource $foundResource)
    {

        $this->foundResource = $foundResource;
    }

    /**
     * @return \Nap\Resource\Resource
     */
    public function getFoundResource()
    {
        return $this->foundResource;
    }

}