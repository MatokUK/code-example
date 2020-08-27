<?php

namespace Matok\Bundle\MediaBundle\Configurator;


class Configuration
{
    private $uploadDirectory;
    private $assetDirectory;

    private $name;

    private $thumbnails = array();

    /** @var bool */
    private $originalSizeAllowed;

    public function __construct($name, $rootDirectory, $directory, $thumbnails, $allowOriginalSize)
    {
        $this->name = $name;
        $this->uploadDirectory = $rootDirectory.$directory;
        $this->assetDirectory = str_replace(DIRECTORY_SEPARATOR, '/', $directory);
        $this->thumbnails = $thumbnails;
        $this->originalSizeAllowed = $allowOriginalSize;
    }

    /**
     * @return mixed
     */
    public function getUploadDirectory()
    {
        return $this->uploadDirectory;
    }

    public function getAssetDirectory()
    {
        return $this->assetDirectory;
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}