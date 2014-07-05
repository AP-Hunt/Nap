<?php
namespace Nap\Test\Application\Stubs;


use Nap\Metadata\ControllerMetadata;

class ControllerMetadataProvider extends \Nap\Metadata\ControllerMetadataProvider
{
    public function __construct()
    {
        // Nothing
    }

    public function getMetadataFor($type)
    {
        $metadata = new ControllerMetadata($type);
        $metadata->setAcceptedMimeTypes("index", array("application/index"));
        $metadata->setAcceptedMimeTypes("get", array("application/get"));
        $metadata->setAcceptedMimeTypes("post", array("application/post"));
        $metadata->setAcceptedMimeTypes("put", array("application/put"));
        $metadata->setAcceptedMimeTypes("delete", array("application/delete"));
        $metadata->setAcceptedMimeTypes("options", array("application/options"));

        $metadata->setDefaultMimeType("index", null);
        $metadata->setDefaultMimeType("get", "application/defaultGet");
        $metadata->setDefaultMimeType("post", "application/post");
        $metadata->setDefaultMimeType("put", "application/put");
        $metadata->setDefaultMimeType("delete", "application/delete");
        $metadata->setDefaultMimeType("options", "application/options");

        return $metadata;
    }

}