<?php

namespace Matok\Bundle\MediaBundle\Image;

use Matok\Bundle\MediaBundle\Repository\MediaRepository;
use Matok\Bundle\MediaBundle\DirBalancer\DirBalancerInterface;
use Matok\Bundle\MediaBundle\Gregwar\Image as GregwarImage;

class Dimension
{
    const FILENAME_SEPARATOR = '@';
    const DIMENSION_SEPARATOR = 'x';

    /** @var MediaRepository */
    private $repository;

    private $dirBalander;

    private $rootDirectory;

    public function __construct($rootDirectory, MediaRepository $repository, DirBalancerInterface $dirBalancer)
    {
        $this->rootDirectory = $rootDirectory;
        $this->repository = $repository;
        $this->dirBalander = $dirBalancer;
    }

    /**
     * @param string $imageHash
     * @param mixed $dimension
     *
     * @return string
     */
    public function getResizedPath($imageHash, $dimension)
    {
        list($width, $height) = $this->parseDimension($dimension);
        list($imageNameWithoutExtension, $extension) = $this->splitToFilenameAndExtension($imageHash);

        return $imageNameWithoutExtension.self::FILENAME_SEPARATOR.$width.self::DIMENSION_SEPARATOR.$height.$extension;
    }

    /**
     * @param int $imageHash
     * @param int $dimension
     *
     * @return bool
     */
    public function isDimensionCreated($imageHash, $dimension)
    {
        list($filename, $extension) = $this->splitToFilenameAndExtension($imageHash);
        $path = $this->repository->getRootDirectoryByHash($filename);
        $balancedPath = $this->dirBalander->balance($imageHash);
        $dimensionPath = $this->getResizedPath($balancedPath, $dimension);

        return is_file($this->rootDirectory.$path.$dimensionPath);
    }

    public function resize($originalImageHash, $dimension, $focus = null)
    {
        list($filename, $extension) = $this->splitToFilenameAndExtension($originalImageHash);
        $path = $this->repository->getRootDirectoryByHash($filename);
        $balancedPath = $this->dirBalander->balance($originalImageHash);
        $dimensionPath = $this->getResizedPath($balancedPath, $dimension);

        list($width, $height) = $this->parseDimension($dimension);
        $resizedImage = GregwarImage::open($this->rootDirectory.$path.$balancedPath)
                            ->setFocus($focus)
                            ->prettyResize($width, $height)
                            ->save($this->rootDirectory.$path.$dimensionPath)
        ;
        //dump($resizedImage);
       // exit;

        return $this->rootDirectory.$path.$dimensionPath;
    }

    private function splitToFilenameAndExtension($filenameWithExtension)
    {
        $lastDot = strrpos($filenameWithExtension, '.');
        $imageNameWithoutExtension = substr($filenameWithExtension, 0, $lastDot);
        $extension = substr($filenameWithExtension, $lastDot);

        return array($imageNameWithoutExtension, $extension);
    }

    /**
     * @param mixed $dimension
     * @return array
     */
    private function parseDimension($dimension)
    {
        if (is_numeric($dimension)) {
            return array($dimension, $dimension);
        }

        $dimension = explode('x', $dimension);

        if (!$this->hasTwoElements($dimension) || !$this->hasOnlyNumericElements($dimension)) {
            throw new \InvalidArgumentException(sprintf('Cannot parse image dimension %s', implode('x', $dimension)));
        }

        return array($dimension[0], $dimension[1]);
    }

    /**
     * @param $input
     * @return bool
     */
    private function hasTwoElements($input)
    {
        return isset($input[1]) && !isset($input[2]);
    }

    /**
     * @param $input
     * @return bool
     */
    private function hasOnlyNumericElements($input)
    {
        foreach ($input as $item) {
            if (!is_numeric($item)) {
                return false;
            }
        }

        return true;
    }
}