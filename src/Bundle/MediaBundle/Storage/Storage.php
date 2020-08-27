<?php

namespace Matok\Bundle\MediaBundle\Storage;

use Matok\Bundle\MediaBundle\Configurator\Configuration;
use Matok\Bundle\MediaBundle\DirBalancer\DirBalancerInterface;
use Matok\Bundle\MediaBundle\Hasher\HasherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Matok\Bundle\MediaBundle\Repository\MediaRepository;

class Storage
{
    /** @var DirBalancerInterface */
    private $dirBalancer;

    /** @var HasherInterface */
    private $filenameHasher;

    /** @var MediaRepository */
    private $repository;

    /** @var  Configuration */
    private $configuration;

    public function __construct(DirBalancerInterface $dirBalancer, HasherInterface $filenameHasher, MediaRepository $repository, Configuration $configuration)
    {
        $this->dirBalancer = $dirBalancer;
        $this->filenameHasher = $filenameHasher;
        $this->repository = $repository;
        $this->configuration = $configuration;

        dump($configuration);
    }

    /**
     * @param int $mediaId
     *
     * @return string
     */
    public function getMediaPath($mediaId)
    {
        $media = $this->repository->getMedia($mediaId);

        return $media['path'].$media['hash'].'.'.$media['extension'];
    }


    public function storeFromUploadedFile($uploadedFile)
    {
        return $this->storeFromLocalFile($uploadedFile->getPathname(), $uploadedFile->getClientOriginalName(), $uploadedFile->guessExtension());
    }

    public function softDeleteMedia($mediaId)
    {
        $this->repository->softDeleteMedia($mediaId);
    }

    private function storeFromLocalFile($localFile, $filename, $extension)
    {
        $this->checkRootDir();

        $path = null;

        do {
            $hash = $this->filenameHasher->hash($filename, $extension);
            $path = $this->configuration->getUploadDirectory().$this->dirBalancer->balance($hash);
        } while(file_exists($path));

        $fileSystem = new Filesystem();
        $fileSystem->copy($localFile, $path);
        $storedId = $this->persistMediaFile($path);

        return $storedId;
    }

    /**
     * @param string $path
     */
    private function persistMediaFile($path)
    {
        $file = new File($path);

        return $this->repository->storeMedia($file, $this->configuration->getAssetDirectory());
    }


    private function checkRootDir()
    {
        if (!is_dir($this->configuration->getUploadDirectory())) {
            throw new \LogicException(sprintf('Root directory error: "%s"', $this->configuration->getUploadDirectory()));
        }
    }

}