<?php

namespace Matok\Bundle\MediaBundle\DirBalancer;

interface DirBalancerInterface
{
    /**
     * @param string $filename
     *
     * @return string
     */
    public function balance($filename);
}