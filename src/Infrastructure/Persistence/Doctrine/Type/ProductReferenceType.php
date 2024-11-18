<?php

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\ValueObject\ProductReference;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class ProductReferenceType extends Type
{
    const PRODUCT_REFERENCE = 'product_reference';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new ProductReference($value);
    }

    public function convertToDatabaseValue($object, AbstractPlatform $platform)
    {
        if (!$object instanceof ProductReference) {
            throw new ConversionException("Expected an instance of ProductReference.");
        }

        return $object->value;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return $sqlExpr;
    }

    public function getName()
    {
        return self::PRODUCT_REFERENCE;
    }
}
