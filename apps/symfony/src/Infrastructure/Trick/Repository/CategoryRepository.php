<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Repository;

use App\Infrastructure\Trick\Contract\Repository\CategoryRepositoryInterface;
use App\Infrastructure\Trick\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
