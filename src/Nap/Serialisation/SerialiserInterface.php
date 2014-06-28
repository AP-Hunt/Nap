<?php
namespace Nap\Serialisation;

interface SerialiserInterface
{
    /**
     * Serialises the input data
     *
     * @param   array $data
     * @return  string
     */
    public function serialise(array $data);
} 