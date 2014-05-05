<?php
namespace Nap\Resource\Parameter;


interface ParameterScheme {
    /**
     * @return \Nap\Resource\Parameter\ParameterInterface[]
     */
    public function getParameters();
} 