<?php

namespace Matok\Bundle\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class UploadedFileToIdTransformer implements DataTransformerInterface
{
    private $storage;

    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function transform($value)
    {
        if (null !== $value) {
            $result = array();
            $result['image_id'] = $value;
            return $result;
        }
    }

    public function reverseTransform($value)
    {
        if (null !== $value['upload']) {
            return $this->storage->storeFromUploadedFile($value['upload']);
        }

        return $value['image_id'];
    }
}