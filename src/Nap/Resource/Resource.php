<?php
namespace Nap\Resource;


class Resource
{
    /** @var string $name */
    private $name;

    /** @var string $uriPartial */
    private $uriPartial;

    /** @var Resource[] $childResources */
    private $childResources;

    /** @var Resource $parent */
    private $parent;

    public function __construct($name, $uriPartial, $childResources = array())
    {
        $this->name = $name;
        $this->uriPartial = $uriPartial;
        $this->parent = null;

        $this->childResources = $childResources;
        array_walk($this->childResources, function(Resource $child){
            $child->setParent($this);
        });
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUriPartial()
    {
        return $this->uriPartial;
    }

    public function getChildResources()
    {
        return $this->childResources;
    }

    public function setParent(Resource $parentResource)
    {
        $this->parent = $parentResource;
    }

    public function getParent()
    {
        return $this->parent;
    }
}