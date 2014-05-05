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

    /** @var \Nap\Resource\Parameter\Scheme */
    private $parameterScheme;

    public function __construct(
        $name,
        $uriPartial,
        \Nap\Resource\Parameter\Scheme $parameterScheme = null,
        $childResources = array()
    ) {
        $this->name = $name;
        $this->uriPartial = $uriPartial;
        $this->parent = null;

        $this->childResources = $childResources;
        array_walk($this->childResources, function(Resource $child){
            $child->setParent($this);
        });

        $this->parameterScheme = $parameterScheme ?: new \Nap\Resource\Parameter\Scheme\None();
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

    public function hasChildren()
    {
        return (count($this->childResources) > 0);
    }

    public function setParent(Resource $parentResource)
    {
        $this->parent = $parentResource;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getParameterScheme()
    {
        return $this->parameterScheme;
    }
}