<?php

namespace Matok\Bundle\BlogWebBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Matok\Bundle\MediaSonataBundle\Entity\Image;

class Article
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $perex;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $publishedAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var int
     */
    private $articleId;

    /**
     * @var integer
     */
    private $topImageId;

    /**
     * @var int
     */
    private $status;

    /**
     * @var ArticleSerie
     */
    private $serie;

    /**
     * @var bool
     */
    private $pinned;

    /**
     * @var bool
     */
    private $showFullSerie = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * @var Collection
     */
    private $tags2;

    /** @var Image */
    private $topImage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->tags2 = new ArrayCollection();
        $this->series = new ArrayCollection();
        $this->pinned = false;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setPerex(string $perex): self
    {
        $this->perex = $perex;

        return $this;
    }

    public function getPerex(): self
    {
        return $this->perex;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setPublishedAt(\DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }


    /**
     * Set topImageId
     *
     * @param int $topImageId
     *
     * @return Article
     */
    public function setTopImageId($topImageId)
    {
        $this->topImageId = $topImageId;

        return $this;
    }

    public function getTopImageId(): ?int
    {
        return $this->topImageId;
    }

    public function setStatus(int $statusId): self
    {
        $this->status = new ArticleStatus($statusId);

        return $this;
    }

    public function getTopImagePath(): ?string
    {
        if (null !== $this->topImage) {
            return $this->topImage->getDirectory().$this->topImage->getHash().'.'.$this->topImage->getExtension();
        }
    }

    public function getStatus(): int
    {
        return $this->status->getArticleStatusId();
    }

    public function isPinned(): bool
    {
        return $this->pinned;
    }

    public function setPinned(bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    public function getShowFullSerie(): bool
    {
        // hotfix
        if (null === $this->showFullSerie) {
            return false;
        }
        return $this->showFullSerie;
    }

    public function setShowFullSerie(bool $showFullSerie): self
    {
        $this->showFullSerie = $showFullSerie;

        return $this;
    }

    public function getSerie(): ?ArticleSerie
    {
        return $this->serie;
    }

    public function setSerie(ArticleSerie $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Add tag
     *
     * @param Tag $tag
     *
     * @return Article
     */
    public function addTopic(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove topic
     *
     * @param Tag $tag
     */
    public function removeTopic(Tag $tag)
    {
        $this->topic->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function getTags2(): Collection
    {
        return $this->tags2;
    }

    public function __toString()
    {
        return (string) $this->title;
    }
}

