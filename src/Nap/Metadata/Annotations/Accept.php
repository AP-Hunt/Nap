<?php
namespace Nap\Metadata\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Accept
{
    /**
     * @var string[]
     */
    private $acceptedMimeTypes;

    public function __construct(array $acceptedMimeTypes)
    {
        $this->acceptedMimeTypes = $acceptedMimeTypes["value"];
    }

    /**
     * @return string[]
     */
    public function getAcceptedMimeTypes()
    {
        return $this->acceptedMimeTypes;
    }
} 
