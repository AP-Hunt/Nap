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

    /** @var \Nap\Resource\Parameter\ParameterScheme */
    private $parameterScheme;

    public function __construct(
        $name,
        $uriPartial,
        \Nap\Resource\Parameter\ParameterScheme $parameterScheme = null,
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUriPartial()
    {
        return $this->uriPartial;
    }

    /**
     * @return array|\Resource[]
     */
    public function getChildResources()
    {
        return $this->childResources;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return (count($this->childResources) > 0);
    }

    /**
     * @param Resource $parentResource
     */
    public function setParent(Resource $parentResource)
    {
        $this->parent = $parentResource;
    }

    /**
     * @return \Nap\Resource\Resource
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Parameter\ParameterScheme
     */
    public function getParameterScheme()
    {
        return $this->parameterScheme;
    }

    /**
     * Returns all parameters, including those of the resource ancestors
     *
     * @return Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        $params = $this->getParameterScheme()->getParameters();
        if($this->getParent() == null){
            return $params;
        }

        $parent = $this->getParent();
        $params = array_merge($params, $parent->getParameters());

        return $params;
    }
}