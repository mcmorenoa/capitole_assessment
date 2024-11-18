<?php

namespace App\Domain\ValueObject;

class ProductReference
{
    public function __construct(public readonly string $value)
    {
        if (!$this->isValid($value)) {
            throw new \Exception("Invalid SKU format. SKU must be a six-digit string, e.g., '000005'.");
        }
    }

    private function isValid(string $sku): bool
    {
        return preg_match('/^\d{6}$/', $sku) === 1;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
