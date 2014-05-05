<?php
namespace Nap\Resource;


class ResourceMatcher
{
    /** @var \Nap\Uri\MatchableUriBuilderInterface $uriBuilder*/
    private $uriBuilder;

    public function __construct(\Nap\Uri\MatchableUriBuilderInterface $pathBuilder)
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