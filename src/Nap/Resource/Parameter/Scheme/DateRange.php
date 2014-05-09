<?php
namespace Nap\Resource\Parameter\Scheme;

use Nap\Resource\Parameter\DateTimeParam;
use Nap\Resource\Parameter\ParameterScheme;

class DateRange implements ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new DateTimeParam("from", true),
            new DateTimeParam("to", true)
        );
    }
}