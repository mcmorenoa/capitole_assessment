<?php

namespace App\Domain\Repository\Category;

use App\Domain\Entity\Category\Category;

interface CategoryRepositoryInterface
{
    public function findOneByName(string $name): ?Category;
}
