<?php
namespace Nap\Resource\Parameter;


abstract class ParameterBase implements ParameterInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $isRequiredForSelf;

    /**
     * @var bool
     */
    private $isRequiredForChildren;

    /**
     * @var string
     */
    private $identifier;

    public function __construct($name, $isRequiredForSelf, $isRequiredForChildren)
    {
        $this->name = $name;
        $this->isRequiredForSelf = $isRequiredForSelf;
        $this->isRequiredForChildren = $isRequiredForChildren;
        $this->identifier = $name."_".rand();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Whether the parameter is mandatory for this resource
     *
     * @return boolean
     */
    public function isRequiredForSelf()
    {
        return $this->isRequiredForSelf;
    }

    /**
     * Whether the parameter is mandatory for this resource's children
     *
     * @return boolean
     */
    public function isRequiredForChildren()
    {
        return $this->isRequiredForChildren;
    }

    /**
     * Get a unique identifier for this parameter, different from the name.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    abstract public function getMatchingExpression();

    /**
     * Converts the matched value in to the intended data type.
     * Returns false when the value could not be converted to the data type.
     *
     * @param   string  $value
     * @return  mixed|false
     */
    abstract public function convertValue($value);

 }