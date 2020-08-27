<?php

namespace Matok\Bundle\BlogWebBundle\Twig;

use Matok\BBCode\Converter\PhpBBConverter;
use Matok\BBCode\Parser;
use Matok\Bundle\BlogWebBundle\BBConverter\BlogSpecific;
use Matok\Bundle\BlogWebBundle\BBConverter\Code as CodeConverter;
use Matok\Bundle\BlogWebBundle\BBConverter\Image as ImageConverter;
use Matok\Bundle\BlogWebBundle\BBConverter\FootNote as FootNoteConverter;
use Matok\Bundle\BlogWebBundle\BBConverter\RelatedArticle;
use Matok\Bundle\BlogWebBundle\BBConverter\SpoilerConverter;
use Matok\Bundle\BlogWebBundle\BBConverter\Url as UrlConverter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BlogWebExtension extends AbstractExtension
{
    /** @var ImageConverter */
    private $bbCodeImageConverter;

    /** @var RelatedArticle */
    private $relatedArticleConverter;

    public function __construct(ImageConverter $converterA, RelatedArticle $converterB)
    {
        $this->bbCodeImageConverter = $converterA;
        $this->relatedArticleConverter = $converterB;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('price', array($this, 'priceFilter')),
            new TwigFilter('convert_bb_code', array($this, 'convertBbCode'), array('is_safe' => array('html'))),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function convertBbCode($text)
    {
        $parser = new Parser($text);
        $parser->registerConverter(new PhpBBConverter());
        $parser->registerConverter(new CodeConverter());
        $parser->registerConverter(new UrlConverter());
        $parser->registerConverter($this->relatedArticleConverter);
        $parser->registerConverter($this->bbCodeImageConverter);
        $parser->registerConverter(new FootNoteConverter());
        $parser->registerConverter(new BlogSpecific());
        $parser->registerConverter(new SpoilerConverter());
        $parser->registerTextCallback(function($text) { return htmlspecialchars($text); } );

        return $parser->parse();
    }
}