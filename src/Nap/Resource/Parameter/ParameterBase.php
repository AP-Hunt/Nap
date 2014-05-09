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
    private $isRequired;

    public function __construct($name, $isRequired)
    {
        $this->name = $name;
        $this->isRequired = $isRequired;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Whether the parameter is mandatory within the route
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->isRequired;
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