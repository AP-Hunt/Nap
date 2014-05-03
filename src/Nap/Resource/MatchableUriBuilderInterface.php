<?php
namespace Nap\Resource;


interface MatchableUriBuilderInterface
{
    /**
     * @param Resource $rootResource
     * @return MatchableUri[]
     */
    public function buildUrisForResource(\Nap\Resource\Resource $rootResource);
} 