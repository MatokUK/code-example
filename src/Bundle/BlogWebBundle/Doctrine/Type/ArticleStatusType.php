<?php

namespace Matok\Bundle\BlogWebBundle\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;
use Matok\Bundle\BlogWebBundle\Entity\ArticleStatus;

class ArticleStatusType extends Type
{
    public function getName()
    {
        return '';
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform instanceof MySqlPlatform) {
            return $platform->getSmallIntTypeDeclarationSQL($fieldDeclaration);
        }

        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new ArticleStatus($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getArticleStatusId();
    }
}