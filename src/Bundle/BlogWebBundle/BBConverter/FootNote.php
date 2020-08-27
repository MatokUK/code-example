<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractConverter;
use Matok\BBCode\Token\TagWithAttributes;

class FootNote extends AbstractConverter
{
    private $notes = array();
    private $noteCounter = 0;

    /**
     * @return array
     */
    public function getKnownTags()
    {
        return array('footnote', 'allnotes');
    }

    /**
     * @param TagWithAttributes $note
     * @return string
     */
    public function convertTagNamedFootnote(TagWithAttributes $note)
    {
        $this->notes[] = $note->getText();

        return '<sup>'.(++ $this->noteCounter).'</sup>';
    }

    public function convertTagNamedAllnotes(TagWithAttributes $note)
    {
        $result = '<br><strong>Footnotes:</strong><p>';
        foreach ($this->notes as $key => $note) {
            $result .= ($key + 1).': '.$note.'<br/>';
        }

        return $result.'</p>';
    }

    public function convertOpeningTag($tag, $attrs)
    {

    }

    public function convertClosingTag($tag, $attrs)
    {

    }
}