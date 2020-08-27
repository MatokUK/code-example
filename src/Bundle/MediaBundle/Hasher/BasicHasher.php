<?php

namespace Matok\Bundle\MediaBundle\Hasher;

class BasicHasher implements HasherInterface
{
    private $hashedNames = array();

    /**
     * @param string $filename
     * @param null $extension
     * @return string
     */
    public function hash($filename, $extension = null)
    {
        if (empty($extension)) {
            $extension = $this->getExtension($filename);
        } else {
            $extension = '.'.$extension;
        }

        $filenameWithRandomString = $filename.time().uniqid();

        return hash('sha256', $filenameWithRandomString).strtolower($extension);
    }

    public function removeExtension($hash)
    {
        $extension = $this->getExtension($hash);

        if (!empty($extension)) {
            $hash = substr($hash, 0, -(strlen($extension) + 1));
        }

        return $hash;
    }

    /**
     * @param string $filename
     * @return string
     */
    private function getExtension($filename)
    {
        $pathinfo = pathinfo($filename);

        return isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
    }
}