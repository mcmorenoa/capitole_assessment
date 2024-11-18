<?php

namespace App\Application\UseCase\Product;

class GetProductsUseCaseRequest
{
    public function __construct(
        public ?string $category = null,
        public ?int    $priceLessThan = null,
        public int     $limit = 5
    )
    {
    }
}
