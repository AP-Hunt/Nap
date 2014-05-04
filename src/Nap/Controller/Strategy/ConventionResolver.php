<?php
namespace Nap\Controller\Strategy;

use Nap\Controller\ControllerResolutionStrategy;

class ConventionResolver implements ControllerResolutionStrategy
{
    /** @var \Nap\Util\FileLoader */
    private $fileLoader;

    public function __construct(\Nap\Util\FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * Resolves a given resource to its controller
     *
     * @param \Nap\Resource\Resource $resource
     * @return \Nap\Controller\NapControllerInterface
     */
    public function resolve(\Nap\Resource\Resource $resource)
    {
        $resourceFolderName = $this->resolveFolderNameForResource($resource->getParent());
        $fileName = $resourceFolderName.$resource->getName()."Controller.php";
        $this->fileLoader->loadFile($fileName);
    }

    private function resolveFolderNameForResource(\Nap\Resource\Resource $resource = null)
    {
        if($resource == null)
        {
            return DIRECTORY_SEPARATOR;
        }

        return $this->resolveFolderNameForResource($resource->getParent())
                .$resource->getName()
                .DIRECTORY_SEPARATOR;
    }
}