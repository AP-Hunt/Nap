<?php
namespace Nap\Resource\Parameter\Scheme;

class None implements \Nap\Resource\Parameter\ParameterScheme
{
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters()
    {
        return array();
    }
}