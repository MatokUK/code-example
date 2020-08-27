<?php

namespace Matok\Bundle\BlogWebBundle\Entity;

class ArticleStatus
{
    public const PUBLISHED = 1;
    public const CONCEPT = 0;

    private const MAP = [
           'enum_article_published' => self::PUBLISHED,
           'enum_article_concept' => self::CONCEPT,
        ];

    /** @var string */
    private $titleKey;

    /** @var int */
    private $articleStatusId;

    public function __construct(int $articleStatusId)
    {
        $this->articleStatusId = $articleStatusId;
        $this->titleKey = array_search($articleStatusId, self::MAP);
    }

    public static function getChoiceList()
    {
        return self::MAP;
    }

    public function getTitleKey(): string
    {
        return $this->titleKey;
    }

    public function getArticleStatusId(): int
    {
        return $this->articleStatusId;
    }

    public function __toString()
    {
        return (string) $this->titleKey;
    }
}
