<?php

namespace App\Domain\ValueObject;

class DiscountPercentage
{
    public function __construct(private int $percentage)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Percentage value must be between 0 and 100.');
        }
    }

    public function getValue(): int
    {
        return $this->percentage;
    }

    public function getFormattedValue(): string
    {
        return $this->percentage . '%';
    }
}
