<?php

namespace App\Domain\Repository\Product;

use App\Domain\Entity\Product\ProductCollection;
use Doctrine\Common\Collections\Criteria;

interface ProductRepositoryInterface
{
    public function match(Criteria $criteria): ProductCollection;
}
