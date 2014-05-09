<?php
namespace Nap\Uri;


use Nap\Resource\Parameter\ParameterInterface;

class MatchableUriBuilder implements MatchableUriBuilderInterface
{
    /**
     * @param Resource $rootResource
     * @return MatchableUri[]
     */
    public function buildUrisForResource(\Nap\Resource\Resource $rootResource)
    {
        return $this->buildPrefixedUrisForResource("", $rootResource);
    }

    private function buildPrefixedUrisForResource($prefix, \Nap\Resource\Resource $rootResource)
    {
        // Make root of uri
        $requiredParams = array_filter($rootResource->getParameterScheme()->getParameters(),
            function(ParameterInterface $param){
                return $param->isRequired();
            }
        );

        $baseUri = array_reduce($requiredParams, function($uri, ParameterInterface $param){
            return $uri."/".$this->parameterMatcher($param);
        }, $prefix.$rootResource->getUriPartial());

        // Start uri collection
        $uris = array();

        // Add root uri
        $uris[] = new MatchableUri($this->makeRegexFromPath($baseUri), $rootResource);

        // Add uris with optional params
        $optionalParams = array_filter($rootResource->getParameterScheme()->getParameters(),
            function(ParameterInterface $param){
                return !$param->isRequired();
            }
        );

        foreach($optionalParams as $param){
            $u = $baseUri."/".$this->parameterMatcher($param);
            $uris[] = new MatchableUri($this->makeRegexFromPath($u), $rootResource);
        }

        foreach($rootResource->getChildResources() as $child)
        {
            $uris = array_merge($uris, $this->buildPrefixedUrisForResource($baseUri, $child));
        }

        return $uris;
    }


    private function makeRegexFromPath($path)
    {
        return sprintf("#^%s$#", $path);
    }

    private function parameterMatcher(ParameterInterface $param)
    {
        return sprintf("(?P<%s>%s)", $param->getName(), $param->getMatchingExpression());
    }
}