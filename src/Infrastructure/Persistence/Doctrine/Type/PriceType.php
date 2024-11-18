<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\Price;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PriceType extends Type
{
    const PRICE = 'price';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return Price::createFromOriginalPrice($value);
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
        return self::PRICE;
    }
}
