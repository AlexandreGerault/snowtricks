<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Repository;

use App\Infrastructure\Trick\Contract\Repository\CategoryRepositoryInterface;
use App\Infrastructure\Trick\Entity\Category;
use App\Infrastructure\Trick\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
