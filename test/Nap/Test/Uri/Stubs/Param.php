<?php
namespace Nap\Test\Uri\Stubs;

class Param implements \Nap\Resource\Parameter\ParameterInterface
{
    private $name;
    private $requiredForSelf;
    private $requiredForChildren;
    private $matchingExpression;

    public function __construct($name, $requiredForSelf, $requiredForChildren, $matchingExpression, $identifier)
    {

        $this->name = $name;
        $this->requiredForSelf = $requiredForSelf;
        $this->requiredForChildren = $requiredForChildren;
        $this->matchingExpression = $matchingExpression;
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        return $this->matchingExpression;
    }

    /**
     * Converts the matched value in to the intended data type
     *
     * @param   string $value
     * @return  mixed
     */
    public function convertValue($value)
    {
        // TODO: Implement convertValue() method.
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
     * Whether the parameter is mandatory for this resource
     *
     * @return boolean
     */
    public function isRequiredForSelf()
    {
        return $this->requiredForSelf;
    }

    /**
     * Whether the parameter is mandatory for this resource's children
     *
     * @return boolean
     */
    public function isRequiredForChildren()
    {
        return $this->requiredForChildren;
    }
}