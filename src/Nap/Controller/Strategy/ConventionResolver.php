<?php
namespace Nap\Controller\Strategy;

use Nap\Controller\ControllerResolutionStrategy;

class ConventionResolver implements ControllerResolutionStrategy
{
    const NAMESPACE_SEPARATOR = "\\";

    /**
     * Resolves a given resource to its controller
     *
     * @param \Nap\Resource\Resource $resource
     * @return string   The FQN of the resource's controller
     */
    public function resolve(\Nap\Resource\Resource $resource)
    {
        $resourceFolderName = $this->resolveFolderNameForResource($resource->getParent());
        $fqn = $resourceFolderName.$resource->getName()."Controller";

        return $fqn;
    }

    private function resolveFolderNameForResource(\Nap\Resource\Resource $resource = null)
    {
        if($resource == null)
        {
            return self::NAMESPACE_SEPARATOR;
        }

        return $this->resolveFolderNameForResource($resource->getParent())
                .$resource->getName()
                .self::NAMESPACE_SEPARATOR;
    }
}