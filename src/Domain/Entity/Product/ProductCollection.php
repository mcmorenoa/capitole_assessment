<?php

namespace App\Domain\Entity\Product;

use App\Domain\Collection\TypedCollection;

class ProductCollection extends TypedCollection
{
    /** @var Product[] $items */
    protected array $items;

    protected function type(): string
    {
        return Product::class;
    }

    public function items(): array
    {
        return $this->items;
    }
}
