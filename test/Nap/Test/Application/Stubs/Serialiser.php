<?php
namespace Nap\Test\Application\Stubs;

use Nap\Serialisation\SerialiserInterface;

class Serialiser implements SerialiserInterface
{

    /**
     * Serialises the input data
     *
     * @param   mixed $data
     * @return  string
     */
    public function serialise($data)
    {
        return "serialised";
    }
}