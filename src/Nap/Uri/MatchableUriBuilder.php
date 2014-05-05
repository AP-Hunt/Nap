<?php
namespace Nap\Uri;


class MatchableUriBuilder implements MatchableUriBuilderInterface
{
    /**
     * @param Resource $rootResource
     * @return MatchableUri[]
     */
    public function buildUrisForResource(\Nap\Resource\Resource $rootResource)
    {
        $uris = array();

        $uriPartialRegex = $rootResource->getUriPartial();
        $path = $this->prependResourceUriPartsToPartial($rootResource->getParent(), $uriPartialRegex);
        $uriRegex = $this->makeRegexFromPath($path);

        $uris[] = new MatchableUri($uriRegex, $rootResource);

        if($rootResource->hasChildren()){
            foreach($rootResource->getChildResources() as $child){
                $uris = array_merge($uris, $this->buildUrisForResource($child));
            }
        }

        return $uris;
    }

    private function prependResourceUriPartsToPartial(\Nap\Resource\Resource $rootResource = null, $uriPartial)
    {
        if($rootResource == null){
            return $uriPartial;
        }

        $newPartial = $rootResource->getUriPartial().$uriPartial;
        return $this->prependResourceUriPartsToPartial($rootResource->getParent(), $newPartial);
    }

    private function makeRegexFromPath($path)
    {
        return sprintf("#^%s$#", $path);
    }
}