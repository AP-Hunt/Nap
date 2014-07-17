<?php
namespace Nap\Serialisation;


class JSON implements SerialiserInterface
{
    /**
     * Serialises the input data
     *
     * @param   mixed $data
     * @return  string
     */
    public function serialise($data)
    {
        return json_encode($data);
    }
}