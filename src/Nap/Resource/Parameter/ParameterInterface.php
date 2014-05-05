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
     * Converts the matched value in to the intended data type
     *
     * @param   string  $value
     * @return  mixed
     */
    public function convertValue($value);
} 