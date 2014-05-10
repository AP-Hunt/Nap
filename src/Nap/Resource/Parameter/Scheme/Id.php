<?php
namespace Nap\Resource\Parameter\Scheme;


use Nap\Resource\Parameter\IntParam;
use Nap\Resource\Parameter\ParameterScheme;

class Id implements ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array(
            new IntParam("id", $required = false)
        );
    }
}