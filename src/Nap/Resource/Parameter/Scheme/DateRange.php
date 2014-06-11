<?php
namespace Nap\Resource\Parameter\Scheme;

use Nap\Resource\Parameter\DateTimeParam;
use Nap\Resource\Parameter\ParameterScheme;

class DateRange implements ParameterScheme
{
    /**
     * @var array
     */
    private $params;

    public function __construct()
    {
        $this->params = array(
            new DateTimeParam("from", true, false),
            new DateTimeParam("to", true, false)
        );
    }


    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return $this->params;
    }
}