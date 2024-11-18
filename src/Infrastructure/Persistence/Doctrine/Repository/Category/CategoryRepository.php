<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Category;

use App\Domain\Entity\Category\Category;
use App\Domain\Repository\Category\CategoryRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\AbstractRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Category::class);
    }

    public function findOneByName(string $name): ?Category
    {
        return $this->findOneBy(['name' => $name]);
    }
}
