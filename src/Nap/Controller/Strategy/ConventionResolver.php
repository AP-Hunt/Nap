<?php
namespace Nap\Controller\Strategy;

use Nap\Controller\ControllerResolutionStrategy;

class ConventionResolver implements ControllerResolutionStrategy
{
    const NAMESPACE_SEPARATOR = "\\";

    /** @var string*/
    private $controllerNamespace;

    public function __construct($controllerNamespace = null)
    {
        $this->controllerNamespace = $controllerNamespace ?: "";
    }

    /**
     * Resolves a given resource to its controller
     *
     * @param \Nap\Resource\Resource    $resource
     * @return string   The FQN of the resource's controller
     */
    public function resolve(\Nap\Resource\Resource $resource)
    {
        $resourceFolderName = $this->resolveFolderNameForResource($resource->getParent());
        $fqn = $resourceFolderName.$resource->getName()."Controller";

        $controllerNamespace = $this->stripEndSlashFromNamespace($this->controllerNamespace);

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

    /**
     * Set the root namespace for resolving controllers
     *
     * @param   string $FQN
     * @return  void
     */
    public function setControllerRootNamesapce($FQN)
    {
        $this->controllerNamespace = $FQN;
    }
}