<?php
namespace Nap\Resource\Parameter;


interface ParameterInterface {

    /**
     * @return string
     */
    public function getName();

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression();

    /**
     * Whether the parameter is mandatory within the route
     *
     * @return boolean
     */
    public function isRequired();

    /**
     * Converts the matched value in to the intended data type.
     * Returns false when the value could not be converted to the data type.
     *
     * @param   string  $value
     * @return  mixed|false
     */
    public function convertValue($value);
} 