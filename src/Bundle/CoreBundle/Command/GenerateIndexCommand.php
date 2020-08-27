<?php

namespace Matok\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Matok\Bundle\CoreBundle\Generator\WebFilesGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateIndexCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('matok:generate:index')
            ->setDescription('Generate index.php file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating Public files');
        $generator = $this->createGenerator();

        $generator->generatePublicFiles();
    }

    protected function createGenerator()
    {
        $rootDir = $this->getApplication()->getKernel()->getRootDir();
        $skeletonDirectory = __DIR__.'/../Resources/Skeleton';
        $templateParams = [
            'secret' => $this->getContainer()->getParameter('admin_password'),
        ];

        return new WebFilesGenerator($skeletonDirectory, $rootDir.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'web', $templateParams);
    }
}
