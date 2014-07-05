<?php
namespace Nap\Metadata;


class ControllerMetadata
{
    /** @var string[,] */
    private $acceptedMimeTypes;

    /** @var string[,] */
    private $defaultMimeTypes;

    /** @var string */
    private $name;

    public function __construct($name)
    {
        $this->acceptedMimeTypes = array(
            "index" => array(),
            "get" => array(),
            "post" => array(),
            "put" => array(),
            "delete" => array(),
            "options" => array(),
        );
        $this->defaultMimeTypes = array(
            "index" => null,
            "get" => null,
            "post" => null,
            "put" => null,
            "delete" => null,
            "options" => null,
        );

        $this->name = $name;
    }

    /**
     * @param   string  $methodName
     * @return  null|string
     */
    public function getAcceptedMimeTypes($methodName)
    {
        if(array_key_exists(strtolower($methodName), $this->acceptedMimeTypes))
        {
            return $this->acceptedMimeTypes[strtolower($methodName)];
        }

        return null;
    }

    public function setAcceptedMimeTypes($methodName, array $mimeTypes)
    {
        if(!array_key_exists(strtolower($methodName), $this->acceptedMimeTypes))
        {
            throw new \InvalidArgumentException("Unknown method");
        }

        $this->acceptedMimeTypes[strtolower($methodName)] = $mimeTypes;
    }

    public function setDefaultMimeType($methodName, $defaultMimeType)
    {
        if(!array_key_exists(strtolower($methodName), $this->defaultMimeTypes))
        {
            throw new \InvalidArgumentException("Unknown method");
        }

        $this->defaultMimeTypes[strtolower($methodName)] = $defaultMimeType;
    }

    public function getDefaultMimeType($methodName)
    {
        if(array_key_exists(strtolower($methodName), $this->defaultMimeTypes))
        {
            return $this->defaultMimeTypes[strtolower($methodName)];
        }

        return null;
    }
}
