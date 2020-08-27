<?php

namespace Matok\Bundle\BlogWebBundle\Entity;

class ArticleStat
{
    /**
     * @var \Matok\Bundle\BlogAdminBundle\Entity\Article
     */
    private $article;


    /**
     * Set article
     *
     * @param \Matok\Bundle\BlogAdminBundle\Entity\Article $article
     *
     * @return ArticleStat
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Matok\Bundle\BlogAdminBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}
