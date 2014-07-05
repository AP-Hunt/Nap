<?php
namespace Nap\Metadata\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class DefaultMime
{
    /** @var string */
    private $defaultMimeType;

    public function __construct($defaultMimeType)
    {
        $this->defaultMimeType = $defaultMimeType["value"];
    }

    /**
     * @return string
     */
    public function getDefaultMimeType()
    {
        return $this->defaultMimeType;
    }

} 