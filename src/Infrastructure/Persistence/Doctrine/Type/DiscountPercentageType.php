<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\DiscountPercentage;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DiscountPercentageType extends Type
{
    const DISCOUNT_PERCENTAGE = 'discount_percentage';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new DiscountPercentage($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $value;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function getName()
    {
        return self::DISCOUNT_PERCENTAGE;
    }
}
