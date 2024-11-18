<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository
{
    public function __construct(EntityManagerInterface $entityManager, string $entityClass)
    {
        $classMetadata = $entityManager->getClassMetadata($entityClass);
        parent::__construct($entityManager, $classMetadata);
    }
}
