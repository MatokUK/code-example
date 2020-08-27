<?php
namespace Matok\Bundle\BlogWebBundle\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Types\Type;

class BlogRepository
{
    private $connection;
    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }





    /**
     * @param $articleId
     * @return mixed
     */
    public function getArticle($articleId)
    {
        $sql = 'SELECT a.*, read_count, usefull FROM article a
                LEFT JOIN article_stat s ON a.article_id = s.article_id
                WHERE
                a.article_id = :id
                AND a.published_at < NOW()
                AND a.article_status_id = :published';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('id', $articleId, Type::INTEGER);
        $statement->bindValue('published', 1, Type::INTEGER);
        $statement->execute();

        return $statement->fetch();
    }



}