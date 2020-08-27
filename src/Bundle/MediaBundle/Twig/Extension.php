<?php

namespace Matok\Bundle\MediaBundle\Twig;

use Matok\Bundle\MediaBundle\DirBalancer\DirBalancerInterface;
use Matok\Bundle\MediaBundle\Hasher\BasicHasher;
use Matok\Bundle\MediaBundle\Repository\MediaRepository;
use Matok\Bundle\MediaBundle\Image\Dimension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    /** @var MediaRepository */
    private $repository;

    /** @var Dimension */
    private $imageDimension;

    /** @var DirBalancerInterface */
    private $dirBalancer;

    public function __construct(MediaRepository $repository, DirBalancerInterface $dirBalancer, $imageDimension = null)
    {
        $this->repository = $repository;
        $this->dirBalancer = $dirBalancer;
        $this->imageDimension = $imageDimension;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('mm_image', array($this, 'image')),
        );
    }

    public function image($imageHashOrId, $dimension = null, $customCenter = false)
    {
        if (is_numeric($imageHashOrId)) {
            return $this->imageById($imageHashOrId, $dimension, $customCenter);
        }

        return $this->imageByHash($imageHashOrId, $dimension, $customCenter);
    }

    private function imageById($imageId, $dimension)
    {
        $image = $this->repository->getMedia($imageId);

        if ($image) {
            if (null !== $dimension) {
                $dimensionIsCreated = $this->imageDimension->isDimensionCreated($image['hash'].'.'.$image['extension'], $dimension);
                if (1 || !$dimensionIsCreated) {
                    $this->imageDimension->resize($image['hash'].'.'.$image['extension'], $dimension);
                }
            }

            $balancedPath = $this->dirBalancer->balance($image['hash'].'.'.$image['extension'], true);
            /*dump($balancedPath, $image);
            dump('/'.$image['path'].$this->imageDimension->getResizedPath($balancedPath, $dimension));
            exit;*/

            if (null !== $dimension) {
                return '/' . $image['path'] . $this->imageDimension->getResizedPath($balancedPath, $dimension);
            }

            return '/' . $image['path'].$balancedPath;

        }
    }

    private function imageByHash($unbalancedPath, $dimension, $customCenter)
    {
        $pos = strrpos($unbalancedPath, '/');
        $filename = substr($unbalancedPath, $pos + 1);
        $path = substr($unbalancedPath, 0, $pos + 1);


        $hasher = new BasicHasher();
        $cleanHash = $hasher->removeExtension($filename);
        /*if (empty($path)) {
            $path = $this->repository->getRootDirectoryByHash($imageHash);
            dump($path);
        }*/

        $balancedPath = $this->dirBalancer->balance($filename, true);


        $dimensionIsCreated = $this->imageDimension->isDimensionCreated($filename, $dimension);

        if (/*1 || */!$dimensionIsCreated) {

            $image = $this->repository->getMediaByHash($cleanHash);
            if (!empty($image['center_x'])) {
                $focus = array($image['center_x'], $image['center_y']);
            } else {
                $focus = null;
            }
            // dump('resizing', $focus);
            $this->imageDimension->resize($unbalancedPath, $dimension, $focus);
        }

        return '/'.$path.$this->imageDimension->getResizedPath($balancedPath, $dimension);
    }
}