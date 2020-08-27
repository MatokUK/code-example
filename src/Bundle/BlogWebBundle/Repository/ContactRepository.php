<?php
namespace Matok\Bundle\BlogWebBundle\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Types\Type;

class ContactRepository
{
    private $connection;

    public function __construct(Connection $connection = null)
    {
        $this->connection = $connection;
    }

    public function storeContactForm($email, $subject, $body, $ipAddress)
    {
        $sql = 'INSERT INTO contact (email, subject, body, ip_address, created_at) VALUES (:email, :subject, :body, :ip_address, NOW())';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue('email', $email, Type::TEXT);
        $statement->bindValue('subject', $subject, Type::TEXT);
        $statement->bindValue('body', $body, Type::TEXT);
        $statement->bindValue('ip_address', $ipAddress, Type::TEXT);
        $statement->execute();
    }

    public function storeEmail($email, $ipAddress)
    {
        $sql = 'INSERT INTO nl (email, created_at, ip_address) VALUES (:email, NOW(), :ip_address)
                ON DUPLICATE KEY UPDATE created_at = NOW()';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue('email', $email, Type::TEXT);
        $statement->bindValue('ip_address', $ipAddress, Type::TEXT);
        $statement->execute();
    }
}