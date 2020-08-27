<?php

namespace Matok\Bundle\CoreBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as BaseGenerator;

class WebFilesGenerator extends BaseGenerator
{
    private $destinationDirectory;
    private $templateParams;

    public function __construct($skeletonDirs, $destinationDirectory, array $templateParams)
    {
        $this->destinationDirectory = $destinationDirectory;
        $this->templateParams = $templateParams;
        $this->setSkeletonDirs($skeletonDirs);
    }

    public function generatePublicFiles()
    {
        $this->renderFile('index.php.twig', $this->destinationDirectory.DIRECTORY_SEPARATOR.'index.php', $this->templateParams);
        $this->renderFile('cookie.php.twig', $this->destinationDirectory.DIRECTORY_SEPARATOR.'cookie.php', $this->templateParams);
    }
}