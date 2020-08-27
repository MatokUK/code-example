<?php

namespace Matok\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class HardDeleteMediaForIdiotsCommand extends Command
{
    private $repository;

    public function __construct($repository= null)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('matok:media:hard-delete-like-idiot')
            ->setDescription('Hard delete assets')
            ;
    }



    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$repository = $this->getContainer()->get('matok_media.repository.media');


        $softDeletedMedia = $this->repository->getSoftDeletedMedia();
        foreach ($softDeletedMedia as $media) {
            dump($media);
        }
    }
}