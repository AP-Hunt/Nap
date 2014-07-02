<?php
namespace Nap\Test\Uri\Stubs;

class IntParam extends Param
{
    public function convertValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

}