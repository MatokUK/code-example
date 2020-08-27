<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractAttributeConverter;

class SpoilerConverter extends  AbstractAttributeConverter
{
    /** @var string */
    private $title;

    public function getKnownTags()
    {
        return ['spoiler'];
    }

    public function getKnownAttributes()
    {
        return ['spoiler' => ['title' => '.*']];
    }

    public function convertAttributeValue($tag, $attributeName, $attributeValue)
    {
        $this->title = $attributeValue;
    }

    public function convertOpeningTag($tag, $convertedAttributes)
    {
        $title = $this->title ?? 'Spoiler Alert: Show it to me!';

        return '<div class="spoiler-alert"><strong class="spoiler-show">'.$title.'</strong><div class="spoiler-hidden">';
    }

    public function convertClosingTag($tag, $convertedAttributes)
    {
        return '</div></div>';
    }
}