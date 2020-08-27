<?php
namespace Matok\Bundle\BlogWebBundle\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Types\Type;
use Matok\Bundle\BlogWebBundle\Entity\ArticleStatus;

class ArticleStatRepository
{
    private $connection;

    private const READ = 1;
    private const HELPFUL = 2;

    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    public function getPublishedArticles()
    {
        $sql = 'SELECT count(*) FROM article WHERE
                article_status_id = :published
                AND published_at < NOW()';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('published', ArticleStatus::PUBLISHED, Type::INTEGER);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public function articleWasDisplayed($articleId, $ipAddress)
    {
        return $this->collectStat($articleId, $ipAddress, self::READ);
    }

    public function articleWasHelpful($articleId, $ipAddress)
    {
        return $this->collectStat($articleId, $ipAddress, self::HELPFUL);
    }

    public function updateArticleStats()
    {
        $dateTime = new \DateTime();

        $this->connection->beginTransaction();
        $stats = $this->getArticleFullStats($dateTime);
        if (!empty($stats)) {
            $this->markArticleFullStatsAsCounted($dateTime);
            $groupedStats = $this->groupStats($stats);
            $this->addPartialStatsToTotal($groupedStats);
        }
        $this->connection->commit();
    }

    private function getArticleFullStats(\DateTime $date)
    {
        $sql = 'SELECT count(*) as total, article_id, stat_type_id FROM article_stat_full
                WHERE counted = 0 AND collected_at < :dt GROUP BY article_id, stat_type_id';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('dt', $date, Type::DATETIME);
        $statement->execute();

        return $statement->fetchAll();
    }

    private function markArticleFullStatsAsCounted(\DateTime $date)
    {
        $sql = 'UPDATE article_stat_full SET counted = 1 WHERE  collected_at < :dt';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue('dt', $date, Type::DATETIME);
        $statement->execute();
    }

    private function groupStats($stats)
    {
        $groupedStats = array();

        foreach ($stats as $articleStat) {
            $articleId = $articleStat['article_id'];
            $type = $articleStat['stat_type_id'];
            $groupedStats[$articleId][$type] = $articleStat['total'];
        }

        return $groupedStats;
    }

    private function addPartialStatsToTotal($stats)
    {
        $sql = 'INSERT INTO article_stat (article_id, read_count, usefull) VALUES ';
        $values = array();

        foreach ($stats as $articleId => $articleStat) {
            $readCount = isset($articleStat[self::READ]) ? $articleStat[self::READ] : 0;
            $helpCount = isset($articleStat[self::HELPFUL]) ? $articleStat[self::HELPFUL] : 0;
            $values[] = '('.$articleId.','.$readCount.','.$helpCount.')';
        }

        $sql .= implode(',', $values);
        $sql .= ' ON DUPLICATE KEY UPDATE read_count = read_count + VALUES(read_count), usefull = usefull + VALUES(usefull)';

        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }

    private function collectStat($articleId, $ipAddress, $statType)
    {
        $sql = 'INSERT INTO article_stat_full (article_id, ip_address, stat_type_id, collected_at)
                VALUES (:article_id, :ip_address, :stat_type, NOW())';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('article_id', $articleId, Type::INTEGER);
        $statement->bindValue('ip_address', $ipAddress, Type::STRING);
        $statement->bindValue('stat_type', $statType, Type::STRING);
        $statement->execute();
    }
}