<?php

namespace Matok\Bundle\BlogWebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArticleStatsCommand extends ContainerAwareCommand
{

   protected function configure()
    {
        $this
            ->setName('matok:blog:article-stat')
            ->setDescription('Compute stat data')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting...');

        $statRepository = $this->getContainer()->get('blog_web.repository.stat');
        $statRepository->updateArticleStats();

        $output->writeln('Finished in XX ');
    }
}