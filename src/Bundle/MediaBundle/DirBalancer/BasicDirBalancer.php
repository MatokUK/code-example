<?php

namespace Matok\Bundle\MediaBundle\DirBalancer;

use Matok\Bundle\MediaBundle\Exception\FilenameTooShortException;

class BasicDirBalancer implements DirBalancerInterface
{
    /**
     * @param $filename
     * @param bool $assetPath
     *
     * @return string
     */
    public function balance($filename, $assetPath = false)
    {
        $this->checkFilenameLength($filename);

        $firstDir = $filename[0];
        $secondDir = substr($filename, 0, 2);
        $thirdDir = substr($filename, 0, 3);

        $balancedPath = $firstDir.DIRECTORY_SEPARATOR.$secondDir.DIRECTORY_SEPARATOR.$thirdDir.DIRECTORY_SEPARATOR.$filename;

        if ($assetPath) {
            $balancedPath = $this->makeAssetPath($balancedPath);
        }

        return $balancedPath;
    }

    public function makeAssetPath($filesystemPath)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $filesystemPath);
    }

    /**
     * @param $filename
     * @throws \Exception
     */
    private function checkFilenameLength($filename)
    {
        if (!isset($filename[2])) {
            throw new FilenameTooShortException();
        }
    }
}