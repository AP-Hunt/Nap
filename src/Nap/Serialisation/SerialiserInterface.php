<?php
namespace Nap\Serialisation;

interface SerialiserInterface
{
    /**
     * Serialises the input data
     *
     * @param   mixed $data
     * @return  string
     */
    public function serialise($data);
} 