<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Entity\Product\Product;
use App\Domain\Entity\Product\ProductCollection;
use App\Domain\Repository\Product\ProductRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Product::class);
    }

    public function match(Criteria $criteria): ProductCollection
    {
        $products = $this->matching($criteria);

        return new ProductCollection($products->toArray());
    }
}
