<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;


use Matok\BBCode\Converter\AbstractConverter;
use Matok\BBCode\Token\Tag;

class ArticleStat extends AbstractConverter
{
    public function getKnownTags()
    {
        return array(Tag::ANY);
    }

    public function convertOpeningTag($tag, $convertedAttributes)
    {
        dump($tag, $convertedAttributes);

    }

    public function convertClosingTag($tag, $convertedAttributes)
    {

    }
}