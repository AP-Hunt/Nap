<?php
namespace Nap\Util;

class FileLoader
{
    private $rootPath;

    public function __construct($rootPath)
    {
        $this->rootPath = realpath($rootPath);
    }

    public function loadFile($relativePath)
    {
        require_once(realpath($this->rootPath.$relativePath));
    }
}