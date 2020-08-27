<?php

namespace Matok\Bundle\MediaBundle\Command;

use Symfony\Component\Console\Command\Command;

class NullCommand extends Command
{
    public function __construct($repository = null)
    {
        parent::__construct('matok:media:hard-delete-like-idiot');
    }
}