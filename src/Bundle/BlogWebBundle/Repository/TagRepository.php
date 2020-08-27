<?php

namespace Matok\Bundle\BlogWebBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Matok\Bundle\BlogWebBundle\Entity\ArticleStatus;
use Matok\Bundle\BlogWebBundle\Entity\Tag;

class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getTags()
    {
        return $this->createQueryBuilder('t')
            ->distinct()
            ->innerJoin('t.articles', 'a')
            ->where('a.publishedAt < CURRENT_TIMESTAMP()')
            ->andWhere('a.status = :published')
            ->setParameter('published', ArticleStatus::PUBLISHED)
            ->orderBy('a.publishedAt', 'ASC')
            ->getQuery()
            ->execute();
    }
}