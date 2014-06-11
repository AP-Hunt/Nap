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
        return $this->buildPrefixedUris("", $rootResource);
    }

    private function buildPrefixedUris($prefix, \Nap\Resource\Resource $rootResource)
    {
        $uris = array();

        // Generate uri for self
        $selfUriRegex = $prefix.$this->generateUriRegexForSelf($rootResource);
        $uris[] = new MatchableUri($this->wrapRegularExpression($selfUriRegex), $rootResource);

        // Generate uri prefix for children
        $parentUriRegex = $prefix.$this->generateUriRegexAsParent($rootResource);
        $uris = array_reduce(
            $rootResource->getChildResources(),
            function(array $allUris, \Nap\Resource\Resource $resource) use($parentUriRegex){
                $allUris = array_merge($allUris, $this->buildPrefixedUris($parentUriRegex, $resource));
                return $allUris;
            },
            $uris);

        return $uris;
    }

    private function generateUriRegexForSelf(\Nap\Resource\Resource $rootResource)
    {
        $params = $rootResource->getParameters();
        $rootUri = $rootResource->getUriPartial();

        $requiredParams = array_filter($params, function(ParameterInterface $p){
            return $p->isRequiredForSelf();
        });

        $optionalParams = array_filter($params, function(ParameterInterface $p){
            return !$p->isRequiredForSelf();
        });

        $uriRegex = $this->appendRequiredParamsToUriRegex($rootUri, $requiredParams);


        if(count($optionalParams) > 0) {
            $uriRegex = $this->appendOptionalParamsToUriRegex($uriRegex, $optionalParams);
        }

        return $uriRegex;
    }

    private function generateUriRegexAsParent(\Nap\Resource\Resource $rootResource)
    {
        $params = $rootResource->getParameters();
        $rootUri = $rootResource->getUriPartial();

        $requiredParams = array_filter($params, function(ParameterInterface $p){
            return $p->isRequiredForChildren();
        });

        $optionalParams = array_filter($params, function(ParameterInterface $p){
            return !$p->isRequiredForChildren();
        });

        $uriRegex = $this->appendRequiredParamsToUriRegex($rootUri, $requiredParams);


        if(count($optionalParams) > 0) {
            $uriRegex = $this->appendOptionalParamsToUriRegex($uriRegex, $optionalParams);
        }

        return $uriRegex;
    }

    /**
     * @param string                $rootUri
     * @param ParameterInterface[]  $requiredParams
     */
    private function appendRequiredParamsToUriRegex($rootUri, array $requiredParams)
    {
        $uri = array_reduce($requiredParams, function($acc, ParameterInterface $param){
                return $acc.$this->createRequiredParamRegex($param);
            }, $rootUri);

        return $uri;
    }

    private function appendOptionalParamsToUriRegex($rootUri, $optionalParams)
    {
        $uri = array_reduce($optionalParams, function($acc, ParameterInterface $param){
                return $acc.$this->createOptionalParamRegex($param);
            }, $rootUri);

        return $uri;
    }

    private function createRequiredParamRegex(ParameterInterface $param)
    {
        return sprintf("(/(?P<%s>%s))", $param->getIdentifier(), $param->getMatchingExpression());
    }

    private function createOptionalParamRegex(ParameterInterface $param)
    {
        return sprintf("(/(?P<%s>%s))?", $param->getIdentifier(), $param->getMatchingExpression());
    }

    private function wrapRegularExpression($uri)
    {
        return sprintf("#^%s/?$#", $uri);
    }
}
