<?php

declare(strict_types=1);

namespace App\Infrastructure\Tricks\Repository;

use App\Infrastructure\Tricks\Contracts\Repository\CategoriesRepositoryInterface;
use App\Infrastructure\Tricks\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoriesRepository extends ServiceEntityRepository implements CategoriesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
