<?php

namespace Matok\Bundle\BlogWebBundle\Entity;

class ArticleTagRatio
{
    /**
     * @var int
     */
    private $articleTagRatioId;

    /**
     * @var int
     */
    private $ratio;

    /**
     * @var Tag
     */
    private $tag;

    /**
     * @var Article
     */
    private $article;

    /**
     * @return int
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    public function setRatio(int $ratio)
    {
        $this->ratio = $ratio;
        return $this;
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
        return $this;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function __toString()
    {
        return $this->getTag()->getTitle().' ... ' . $this->ratio;
    }
}
