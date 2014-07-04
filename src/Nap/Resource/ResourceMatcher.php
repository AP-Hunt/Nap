<?php
namespace Nap\Resource;


use Nap\Events\ResourceMatchingEvents;
use Nap\Events\ResourceMatching\ResourceMatchedEvent;
use Nap\Events\ResourceMatching\ResourceNotMatchedEvent;

class ResourceMatcher
{
    use \Nap\Events\EventedTrait;

    /** @var \Nap\Uri\MatchableUriBuilderInterface $uriBuilder*/
    private $uriBuilder;

    public function __construct(\Nap\Uri\MatchableUriBuilderInterface $pathBuilder)
    {
        $this->uriBuilder = $pathBuilder;
    }

    /**
     * Matches a URI against a set of resources
     *
     * @param   string          $uri
     * @param   Resource[]      $resources
     * @return  MatchedResource|null
     */
    public function match($uri, array $resources)
    {
        /** @var Resource $rootResource */
        foreach($resources as $rootResource)
        {
            $resourceUris = $this->uriBuilder->buildUrisForResource($rootResource);

            foreach($resourceUris as $resUri){
                if($resUri->matches($uri)){
                    $matched = new \Nap\Resource\MatchedResource($resUri->getResource(), $resUri->getParameters($uri));

                    $this->dispatchEvent(ResourceMatchingEvents::RESOURCE_MATCHED, new ResourceMatchedEvent($matched));
                    return $matched;
                }
            }
        }

        $this->dispatchEvent(ResourceMatchingEvents::RESOURCE_NOT_MATCHED, new ResourceNotMatchedEvent($uri));
        return null;
    }
}