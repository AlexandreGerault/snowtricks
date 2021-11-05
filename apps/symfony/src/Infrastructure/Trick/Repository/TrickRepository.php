<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Repository;

use App\Infrastructure\Trick\Contract\Repository\CategoryRepositoryInterface;
use App\Infrastructure\Trick\Entity\Illustration;
use App\Infrastructure\Trick\Entity\Trick;
use App\Infrastructure\Trick\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Trick\Entity\Trick as DomainTrick;
use Domain\Trick\Entity\TrickOverview;
use Domain\Trick\Gateway\TrickGateway;

/**
 * @extends ServiceEntityRepository<Trick>
 */
class TrickRepository extends ServiceEntityRepository implements TrickGateway
{
    public function __construct(ManagerRegistry $registry, private CategoryRepositoryInterface $categoryRepository)
    {
        parent::__construct($registry, Trick::class);
    }

    public function getLastTricksOverviews(int $quantity): array
    {
        $query = $this->createQueryBuilder('t');

        $results = $query
            ->addSelect(['category', 'thumbnail'])
            ->join('t.category', 'category')
            ->join('t.thumbnail', 'thumbnail')
            ->orderBy('t.name', 'DESC')
            ->setMaxResults($quantity)
            ->getQuery()
            ->getResult();

        return array_map(function (Trick $trick) {
            return new TrickOverview(
                name: $trick->getName(),
                category: $trick->getCategory()->getName(),
                thumbnail: $trick->getThumbnail()->getPath()
            );
        }, $results);
    }

    public function isNameAvailable(string $name): bool
    {
        $result = $this
            ->createQueryBuilder('t')
            ->where('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getFirstResult();

        if (!$result) {
            return true;
        }

        return false;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(DomainTrick $trick): void
    {
        $trickEntity = new Trick();

        $trickEntity->setName($trick->getName());
        $trickEntity->setDescription($trick->getDescription());
        $trickEntity->setCategory(
            $this->categoryRepository->findOneBy(['name' => $trick->getCategory()])
            ?? throw new \RuntimeException('The category is invalid')
        );

        $thumbnail = (new Illustration())->setTrick($trickEntity)->setPath($trick->getThumbnailUrl());
        $this->_em->persist($thumbnail);

        $trickEntity->setThumbnail($thumbnail);

        foreach ($trick->getIllustrationsPath() as $path) {
            $illustration = new Illustration();
            $illustration->setPath($path);
            $illustration->setTrick($trickEntity);

            $trickEntity->addIllustration($illustration);
            $this->_em->persist($illustration);
        }

        foreach ($trick->getVideoLinks() as $link) {
            $video = new Video();
            $video->setLink($link);
            $video->setTrick($trickEntity);

            $trickEntity->addVideo($video);
            $this->_em->persist($video);
        }

        $this->_em->persist($trickEntity);
        $this->_em->flush();
    }
}
