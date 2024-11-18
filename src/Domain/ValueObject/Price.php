<?php

namespace App\Domain\ValueObject;

class Price
{
    private Currency $currency = Currency::EUR;

    public function __construct(
        private int                 $original,
        private int                 $final,
        private ?DiscountPercentage $discountPercentage
    )
    {
        if (!$this->isValid($original, $final, $discountPercentage)) {
            throw new \Exception('Price is not valid');
        }
    }

    private function isValid(int $original, int $final, ?DiscountPercentage $discountPercentage): bool
    {
        if ($original < 0 || $final < 0) {
            return false;
        }

        if (!is_null($discountPercentage)) {
            $expectedFinal = $original * (1 - $discountPercentage->getValue() / 100);
            return $expectedFinal === $final;
        }

        return $original === $final;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }

    public function getOriginal(): int
    {
        return $this->original;
    }

    public function setOriginal(int $original): void
    {
        $this->original = $original;
    }

    public function getFinal(): int
    {
        return $this->final;
    }

    public function setFinal(int $final): void
    {
        $this->final = $final;
    }

    public function getDiscountPercentage(): ?DiscountPercentage
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?DiscountPercentage $discountPercentage): void
    {
        $this->discountPercentage = $discountPercentage;
    }

    public function applyDiscount(?DiscountPercentage $discountPercentage): void
    {
        if (is_null($discountPercentage)) {
            return;
        }

        $this->final = round($this->original * (1 - $discountPercentage->getValue() / 100));

        $this->discountPercentage = $discountPercentage;
    }

    public static function createFromOriginalPrice(int $original): self
    {
        return new self($original, $original, null);
    }
}
