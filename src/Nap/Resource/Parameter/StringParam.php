<?php
namespace Nap\Resource\Parameter;

class StringParam extends ParameterBase
{

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        return "[A-Za-z0-9\-._~:/?\[\]@1\$&'\(\)*\+,;=]+";
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
        return strval($value);
    }
}