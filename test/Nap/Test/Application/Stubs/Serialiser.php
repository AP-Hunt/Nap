<?php
namespace Nap\Test\Application\Stubs;

use Nap\Serialisation\SerialiserInterface;

class Serialiser implements SerialiserInterface
{

    /**
     * Serialises the input data
     *
     * @param   array $data
     * @return  string
     */
    public function serialise(array $data)
    {
        return "serialised";
    }
}