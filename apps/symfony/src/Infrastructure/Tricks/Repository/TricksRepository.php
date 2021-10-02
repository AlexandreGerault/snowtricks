<?php

declare(strict_types=1);

namespace App\Infrastructure\Tricks\Repository;

use App\Infrastructure\Tricks\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Tricks\Entity\Trick as DomainTrick;
use Domain\Tricks\Gateway\TricksGateway;

class TricksRepository extends ServiceEntityRepository implements TricksGateway
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function getLastTricks(int $quantity): array
    {
        $query = $this->createQueryBuilder("t");

        $results = $query->orderBy('t.name', 'DESC')
                         ->setMaxResults($quantity)
                         ->getQuery()
                         ->getArrayResult();

        return array_map(function (array $a) {
            return new DomainTrick($a['name'], $a['imageUrl']);
        }, $results);
    }
}
