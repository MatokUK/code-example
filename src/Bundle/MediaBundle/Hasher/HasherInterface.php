<?php

namespace Matok\Bundle\MediaBundle\Hasher;

interface HasherInterface
{
    /**
     * @param string $filename
     * @param string $extension
     * @return string
     */
    public function hash($filename, $extension = null);

    /**
     * @param string $hash
     * @return string
     */
    public function removeExtension($hash);
}