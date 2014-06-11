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
     * Whether the parameter is mandatory for this resource
     *
     * @return boolean
     */
    public function isRequiredForSelf();

    /**
     * Whether the parameter is mandatory for this resource's children
     *
     * @return boolean
     */
    public function isRequiredForChildren();

    /**
     * Get a unique identifier for this parameter, different from the name.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Converts the matched value in to the intended data type.
     * Returns false when the value could not be converted to the data type.
     *
     * @param   string  $value
     * @return  mixed|false
     */
    public function convertValue($value);
} 