<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractAttributeConverter;
use Matok\BBCode\Token\TagWithAttributes;

class Code extends AbstractAttributeConverter
{
    private $attributeMap = array(
        'code' => array('syntax' => '.+', 'download' => '\d+', 'bad-example' => '\d+')
    );

    public function getKnownTags()
    {
        return array('code');
    }

    public function getKnownAttributes()
    {
        return $this->attributeMap;
    }

    public function convertOpeningTag($tag, $attrs)
    {
        return '<'.$tag.$this->getAttributePlaceholder().'>';
    }

    public function convertClosingTag($tag, $attrs)
    {
        return '</'.$tag.'>';
    }

    public function convertAttributeName($tag, $attributeName)
    {
        return $attributeName;
    }

    public function convertTagNamedCode(TagWithAttributes $codeTag)
    {
        $badClass = '';
        if ($codeTag->hasAttribute('bad-example')) {
            $badClass = ' class="bad-code"';
        }

        $result = '<pre'.$badClass.'><code';

        if ($codeTag->hasAttribute('syntax')) {
            $result .= ' class="'.$codeTag->getAttribute('syntax').'"';
        }

        $result .= '>';
        $result .= htmlspecialchars(ltrim($codeTag->getText()));

        $result .= $this->convertClosingTag($codeTag->getTagName(), '' /* todo: make optional */).'</pre>';

        return $result;
    }

    public function getPriority()
    {
        return 200;
    }
}