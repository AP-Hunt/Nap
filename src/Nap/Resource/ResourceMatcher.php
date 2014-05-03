<?php
namespace Nap\Resource;


class ResourceMatcher
{
    /** @var MatchableUriBuilderInterface $uriBuilder*/
    private $uriBuilder;

    public function __construct(MatchableUriBuilderInterface $pathBuilder)
    {
        $this->uriBuilder = $pathBuilder;
    }

    public function match($uri, Resource $rootResource)
    {
        $resourceUris = $this->uriBuilder->buildUrisForResource($rootResource);

        foreach($resourceUris as $resUri){
            if($resUri->matches($uri)){
                return $resUri->getResource();
            }
        }

        return null;
    }
}