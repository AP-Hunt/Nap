<?php
namespace Nap\Controller\Strategy;

use Nap\Controller\ControllerResolutionStrategy;

class ConventionResolver implements ControllerResolutionStrategy
{
    const NAMESPACE_SEPARATOR = "\\";

    /**
     * Resolves a given resource to its controller
     *
     * @param string                    $controllerNamespace
     * @param \Nap\Resource\Resource    $resource
     * @return string   The FQN of the resource's controller
     */
    public function resolve($controllerNamespace, \Nap\Resource\Resource $resource)
    {
        $resourceFolderName = $this->resolveFolderNameForResource($resource->getParent());
        $fqn = $resourceFolderName.$resource->getName()."Controller";

        $controllerNamespace = $this->stripEndSlashFromNamespace($controllerNamespace);

        return $controllerNamespace.$fqn;
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

    private function stripEndSlashFromNamespace($namespace)
    {
        $len = strlen($namespace);
        if($len == 0){
            return $namespace;
        }

        if($namespace[$len-1] === self::NAMESPACE_SEPARATOR)
        {
            return substr($namespace, 0, $len-2);
        }

        return $namespace;
    }
}