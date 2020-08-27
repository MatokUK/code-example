<?php

namespace Matok\Bundle\BlogWebBundle\BBConverter;

use Matok\BBCode\Converter\AbstractAttributeConverter;
use Matok\BBCode\Token\TagWithAttributes;
use Matok\Bundle\BlogWebBundle\Repository\BlogRepository;
use Matok\Bundle\MediaBundle\DirBalancer\DirBalancerInterface;
use Matok\Bundle\MediaBundle\Image\Dimension;
use Matok\Bundle\MediaBundle\Repository\MediaRepository;
use Symfony\Component\Routing\RouterInterface;

class RelatedArticle extends AbstractAttributeConverter
{
    private $blogRepository;
    private $mediaRepository;
    private $imageDimension;
    private $dirBalancer;

    private $attributeMap = array(
        'article' => array('id' => '[0-9]+')
    );

    public function __construct(BlogRepository $repository, MediaRepository $mediaRepository, Dimension $imageDimension, DirBalancerInterface $dirBalancer, RouterInterface $router)
    {
        $this->blogRepository = $repository;
        $this->mediaRepository = $mediaRepository;
        $this->router = $router;
        $this->imageDimension = $imageDimension;
        $this->dirBalancer = $dirBalancer;
    }

    public function getKnownTags()
    {
        return array('article');
    }

    public function getKnownAttributes()
    {
        return $this->attributeMap;
    }

    public function convertAttributeValue($tag, $attributeName, $attributeValue)
    {

    }

    public function convertTagNamedArticle(TagWithAttributes $article)
    {
        $dimension = '100x100';
        $relatedArticle = $this->blogRepository->getArticle($article->getAttribute('id'));

        $url = $this->router->generate('blog_article', array('slug' => $relatedArticle['slug'], 'articleId' => $article->getAttribute('id')));
        $image = $this->mediaRepository->getMedia($relatedArticle['top_image_id']);

        $isDimensionCreated = $this->imageDimension->isDimensionCreated($image['hash'].'.'.$image['extension'], $dimension);
        if (!$isDimensionCreated) {
            $this->imageDimension->resize($image['hash'].'.'.$image['extension'], $dimension, array($image['center_x'], $image['center_y']));
        }


        $balancedPath = $this->dirBalancer->balance($image['hash'].'.'.$image['extension'], true);

        $image = '/' . $image['path'] . $this->imageDimension->getResizedPath($balancedPath, $dimension);


        return '<div class="related-article"><div class="pull-right"><img src="'.$image.'"></div><strong>Read related article:</strong><br>
                <a href="'.$url.'">'.$relatedArticle['title'].'</a><br>'.$relatedArticle['perex'].'<div class="clearfix"></div></div>';
    }

    public function convertOpeningTag($tag, $convertedAttributes)
    {

    }

    public function convertClosingTag($tag, $convertedAttributes)
    {

    }
}
