<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractAttributeConverter;
use Matok\Bundle\MediaBundle\DirBalancer\DirBalancerInterface;
use Matok\Bundle\MediaBundle\Repository\MediaRepository;

class Url extends AbstractAttributeConverter
{
    private $friendlyTarget;

    private $attributeMap = array(
        'url' => ['target' => '.+']
    );

    public function getKnownTags()
    {
        return ['url'];
    }

    public function getKnownAttributes()
    {
        return $this->attributeMap;
    }

    public function convertOpeningTag($tag, $convertedAttributes)
    {
        $openInTab = '';
        if (!$this->friendlyTarget) {
            $openInTab = ' target="_blank"';
        }

        return '<a'.$convertedAttributes.$openInTab.'>';
    }

    public function convertClosingTag($tag, $convertedAttributes)
    {
        return '</a>';
    }

    public function convertAttributeName($tag, $attributeName)
    {
        return 'href';
    }

    public function convertAttributeValue($tag, $attributeName, $attributeValue)
    {
        $host = parse_url($attributeValue, PHP_URL_HOST);
        $this->friendlyTarget = empty($host) || strpos($host, 'matok.me.uk') !== false || strpos($host, 'blog.lo') !== false;

        //dump($attributeValue);
        return $attributeValue;
    }

    public function getPriority()
    {
        return 200;
    }
}