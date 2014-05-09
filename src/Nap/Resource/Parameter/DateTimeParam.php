<?php
namespace Nap\Resource\Parameter;

class DateTimeParam extends ParameterBase
{

    /**
     * Returns a regular expression matching the parameter
     *
     * @return string
     */
    public function getMatchingExpression()
    {
        // Match anything that contains the things a date can contain
        // and allow convertValue to return false if it's not actually a date
        return "[0-9\-:TZ]+";
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
        // Use date_create to make a DateTime object or return false
        return date_create($value);
    }
}