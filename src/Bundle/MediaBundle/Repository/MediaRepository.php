<?php

namespace Matok\Bundle\MediaBundle\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpFoundation\File\File;

class MediaRepository
{
    private $connection;

    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    public function storeMedia(File $file, $rootDirectory = null)
    {
        $rootDirectoryId = $this->getRootDirectoryId($rootDirectory);

        $sql = 'INSERT INTO media_image (hash, extension, mine_type, file_size, root_dir_id, created_at)
                VALUES (:hash, :extension, :mine_type, :file_size, :root_dir_id, NOW())';

        $statement = $this->connection->prepare($sql);

        $statement->bindValue('hash', $file->getBasename('.'.$file->getExtension()), Type::STRING);
        $statement->bindValue('extension', $file->getExtension(), Type::STRING);
        $statement->bindValue('mine_type', $file->getMimeType(), Type::STRING);
        $statement->bindValue('file_size', $file->getSize(), Type::INTEGER);
        $statement->bindValue('root_dir_id', $rootDirectoryId, Type::INTEGER);
        $statement->execute();

        return $this->connection->lastInsertId();
    }

    /**
     * @param int $mediaId
     */
    public function softDeleteMedia($mediaId)
    {
        $sql = 'UPDATE media_image SET deleted = 1 WHERE image_id = :id';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue('id', $mediaId, Type::INTEGER);
        $statement->execute();
    }

    /**
     * @param int $mediaId
     */
    public function getMedia($mediaId)
    {
        $sql = 'SELECT m.*, d.path FROM media_image m
                INNER JOIN media_root_dir d ON m.root_dir_id = d.root_dir_id
                WHERE image_id = :id';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('id', $mediaId, Type::INTEGER);
        $statement->execute();

        return $statement->fetch();
    }

    public function getMediaByHash($hash)
    {
        $sql = 'SELECT m.*, d.path FROM media_image m
                INNER JOIN media_root_dir d ON m.root_dir_id = d.root_dir_id
                WHERE m.hash = :hash';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('hash', $hash, Type::STRING);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @param array $ids
     */
    public function getMediaByIds($ids)
    {
        $filteredIds = array_filter($ids);

        if (empty($filteredIds)) {
            return array();
        }

        $placeholder = str_repeat('?,', count($filteredIds) - 1);
        $sql = 'SELECT m.*, CONCAT(m.hash, ".", m.extension) hash_and_extension, d.path FROM media_image m
                INNER JOIN media_root_dir d ON m.root_dir_id = d.root_dir_id
                WHERE image_id IN('.$placeholder.'?)';
        $statement = $this->connection->prepare($sql);
        $statement->execute(array_values($filteredIds));

        return $statement->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE);
    }

    public function getRootDirectoryId($path)
    {
        $sql = 'SELECT root_dir_id FROM media_root_dir WHERE path = :path';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('path', $path, Type::STRING);
        $statement->execute();

        $rootDirectoryId = $statement->fetchColumn();

        if (!$rootDirectoryId) {
            return $this->storeRootDirectory($path);
        }

        return $rootDirectoryId;
    }

    /**
     * @param string $path
     * @return int
     */
    public function storeRootDirectory($path)
    {
        $sql = 'INSERT INTO media_root_dir (path) VALUES (:path)';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('path', $path, Type::STRING);
        $statement->execute();

        return $this->connection->lastInsertId();
    }

    public function getRootDirectoryByHash($hash)
    {
        $sql = 'SELECT d.path FROM media_root_dir d
                INNER JOIN media_image m ON d.root_dir_id = m.root_dir_id
                WHERE m.hash = :hash';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue('hash', $hash, Type::STRING);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function getSoftDeletedMedia()
    {
        $sql = 'SELECT i.*, d.path FROM media_image i
                INNER JOIN media_root_dir d
                WHERE i.deleted = 1
                LIMIT 50';

        $statement = $this->connection->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE);
    }

}