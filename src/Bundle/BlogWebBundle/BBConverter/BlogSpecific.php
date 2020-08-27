<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractConverter;

class BlogSpecific extends AbstractConverter
{
    private $tagMap = array(
        'p' => 'p',
        'hl' => 'code',
        'kb' => 'kbd',
        'h2' => 'h2',
        'h3' => 'h3',
        'ul' => 'ul',
        'ol' => 'ol',
        'li' => 'li',
        'br' => 'br',
        'sub' => 'sub',
        'sup' => 'sup',
    );

    /**
     * @return array
     */
    public function getKnownTags()
    {
        return array_keys($this->tagMap);
    }

    public function getAutoClosings()
    {
        return [
            'li' => ['li'],
            'ol' => ['li', 'ul', 'ol'],
            'ul' => ['li', 'ul', 'ol'],
        ];
    }

    public function convertOpeningTag($tag, $attrs)
    {
        return '<'.$this->tagMap[$tag].'>';
    }

    public function convertClosingTag($tag, $attrs)
    {
        if ('br' !== $tag) {
            return '</'.$this->tagMap[$tag].'>';
        }
    }

    public function getPriority()
    {
        return 1000;
    }
}