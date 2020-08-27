<?php

namespace Matok\Bundle\BlogWebBundle\Slugger;

class Slugger
{
    public static function toSlug($text)
    {
        $text = strtr($text, [':' => '', '/' => '', '(' => '', ')' => '', '[' => '' , ']' => '']);
        $patterns = array('/\s+/');
        $replacements = array('-');

        return strtolower(preg_replace($patterns, $replacements, trim($text)));
    }
}