<?php

namespace Matok\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class HardDeleteMediaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('matok:media:hard-delete')
            ->setDescription('Hard delete of assets')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting...');
        $repository = $this->getContainer()->get('matok_media.repository.media');

       // $rootDirectory = $this->getContainer()->getParameter('kernel.root_dir');
       // $rootDirectory = $rootDirectory.'/../web';


        $softDeletedMedia = $repository->getSoftDeletedMedia();
        foreach ($softDeletedMedia as $media) {
            dump($media);
        }

       /* $finder = new Finder();
        $fileSystem = new Filesystem();

        $files = $finder->files()->in($targetDirecotry)->name('*@*x*');
        foreach  ($files as $file) {
            var_dump($file->getRealPath());
            //$fileSystem->remove($file->getRealPath());

        }*/
    }
}