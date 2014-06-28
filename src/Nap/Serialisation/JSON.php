<?php
namespace Nap\Serialisation;


class JSON implements SerialiserInterface
{
    /**
     * Serialises the input data
     *
     * @param   array $data
     * @return  string
     */
    public function serialise(array $data)
    {
        return json_encode($data);
    }
}