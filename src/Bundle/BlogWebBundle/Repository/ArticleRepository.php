<?php

namespace Matok\Bundle\BlogWebBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Matok\Bundle\BlogWebBundle\Entity\Article;
use Matok\Bundle\BlogWebBundle\Entity\ArticleStatus;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticlesForHomepage(int $page = 1, int $count = 10)
    {
        return $this->createQueryBuilder('a')
            ->where('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->orderBy('a.pinned', 'desc')
            ->addOrderBy('a.publishedAt', 'desc')
            ->setFirstResult($count * ($page-1))
            ->setMaxResults($count + 1)
            ->getQuery()
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->execute();
    }

    public function getPublishedArticle(int $articleId)
    {
        return $this->createQueryBuilder('a')
            ->where('a.articleId = :id')
            ->andWhere('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->getQuery()
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->setParameter('id', $articleId)
            ->getOneOrNullResult();
    }

    /**
     * TODO: pagination
     * @param $tagId
     * @param int $count
     * @return mixed
     */
    public function getArticlesByTag(int $tagId, $count = 10)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.tags', 't')
            ->where('t.tagId = :tag_id')
            ->andWhere('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->setMaxResults($count)
            ->getQuery()
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->setParameter('tag_id', $tagId)
            ->execute();
    }

    public function getNewestArticles($count = 5)
    {
        return $this->createQueryBuilder('a')
            ->where('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->orderBy('a.publishedAt', 'desc')
            ->setMaxResults($count)
            ->getQuery()
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->execute();
    }

    public function findSerieArticles(Article $article)
    {
        if (null === $article->getSerie()) {
            return [];
        }

        if ($article->getShowFullSerie()) {
            return $this->fullSerieList($article);
        }

        return $this->articlePublishedAround($article);
    }

    private function fullSerieList(Article $article)
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.serie = :serie')
            ->andWhere('a.articleId != :article')
            ->andWhere('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->setParameter('serie', $article->getSerie()->getSerieId())
            ->setParameter('article', $article->getArticleId())
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->orderBy('a.publishedAt', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    private function articlePublishedAround(Article $article)
    {
        $qbOlder = $this->relatedSerieQueryBuilder($article);
        $qbOlder->andWhere('a.publishedAt < :date')
            ->setParameter('date', $article->getPublishedAt())
            ->orderBy('a.publishedAt', 'DESC');

        $qbNewer = $this->relatedSerieQueryBuilder($article);
        $qbNewer->andWhere('a.publishedAt > :date')
            ->setParameter('date', $article->getPublishedAt())
            ->orderBy('a.publishedAt', 'ASC');


        return array_filter([$qbOlder->getQuery()->getOneOrNullResult(), $qbNewer->getQuery()->getOneOrNullResult()]);
    }

    private function relatedSerieQueryBuilder(Article $article)
    {
        return $this->createQueryBuilder('a')
            ->where('a.serie = :serie')

            ->andWhere('a.status = :published')
            ->andWhere('a.publishedAt < CURRENT_TIMESTAMP()')
            ->setParameter('serie', $article->getSerie()->getSerieId())
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->setMaxResults(1);
    }

    /**
     * TODO: search in perex, limit search string, pagination
     * @param string $search
     *
     * @return array
     */
    public function searchArticles(string $search)
    {
        return $this->createQueryBuilder('a')
            ->where('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->andWhere('(a.title LIKE :search OR a.content LIKE :search)')
            ->orderBy('a.publishedAt', 'desc')
           // ->setMaxResults($count)
            ->getQuery()
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->setParameter('search', '%'.$search.'%')
            ->execute();
    }
}