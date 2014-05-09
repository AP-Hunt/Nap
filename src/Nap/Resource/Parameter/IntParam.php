<?php
namespace Nap\Resource\Parameter;


class IntParam extends ParameterBase
{
    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        return "\d+";
    }

    /**
     * Converts the matched value in to the intended data type.
     * Returns false when the value could not be converted to the data type.
     *
     * @param   string  $value
     * @return  mixed|false
     */
    public function convertValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }
}