<?php
namespace Nap\Serialisation;


class SerialiserRegistry
{
    private $serialisers;

    public function __construct()
    {
        $this->serialisers = array();
    }

    public function registerSerialiser($mimeType, SerialiserInterface $serialiser)
    {
        $this->serialisers[$mimeType] = $serialiser;
    }

    /**
     * @param   string  $mimeType
     * @return  SerialiserInterface|null
     */
    public function getSerialiser($mimeType)
    {
        if(array_key_exists($mimeType, $this->serialisers))
        {
            return $this->serialisers[$mimeType];
        }

        return null;
    }
} 