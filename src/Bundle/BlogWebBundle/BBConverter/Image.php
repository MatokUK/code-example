<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractAttributeConverter;
use Matok\Bundle\MediaBundle\DirBalancer\DirBalancerInterface;
use Matok\Bundle\MediaBundle\Repository\MediaRepository;

class Image extends AbstractAttributeConverter
{
    /** @var MediaRepository */
    private $repository;

    /** @var DirBalancerInterface */
    private $dirBalancer;

    private $originalDimension = false;

    public function __construct(MediaRepository $repository, DirBalancerInterface $dirBalancer)
    {
        $this->repository = $repository;
        $this->dirBalancer = $dirBalancer;
    }

    private $attributeMap = array(
        'image' => array('id' => '[0-9]+', 'resize' => '.*')
    );

    public function getKnownTags()
    {
        return array('image');
    }

    public function getKnownAttributes()
    {
        return $this->attributeMap;
    }

    public function convertOpeningTag($tag, $convertedAttributes)
    {
        if ($this->originalDimension) {
            $this->originalDimension = false;
            return '<div class="row">
        <div class="center-block"><'.$tag.$convertedAttributes.' /></div></div>';
        }

        return '<div class="row"><'.$tag.$convertedAttributes.' class="col-md-12 col-sm-12" /></div>';
    }

    public function convertClosingTag($tag, $convertedAttributes)
    {
        return '';
    }

    public function convertAttributeName($tag, $attributeName)
    {
        return 'src';
    }

    public function convertAttributeValue($tag, $attributeName, $attributeValue)
    {
        if ('id' == $attributeName) {
            $image = $this->repository->getMedia($attributeValue);
            //dump($image);

            return '/' . $image['path'] . $this->dirBalancer->balance($image['hash'], true) . '.' . $image['extension'];
        }

        if ('resize' === $attributeName && 'no' === $attributeValue) {
            $this->originalDimension = true;
        }
    }

    public function getPriority()
    {
        return 200;
    }
}