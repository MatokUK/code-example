<?php

namespace Matok\Bundle\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class ImageCenterPointTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        dump($value);
    }

    public function reverseTransform($value)
    {
        dump($value);
    }
}