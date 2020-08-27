<?php

namespace Matok\Bundle\BlogWebBundle\Entity;


class ArticleSerie
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var integer
     */
    private $serieId;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ArticleSerie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get serieId
     *
     * @return integer
     */
    public function getSerieId()
    {
        return $this->serieId;
    }

    public function __toString()
    {
        return (string) $this->title;
    }
}
