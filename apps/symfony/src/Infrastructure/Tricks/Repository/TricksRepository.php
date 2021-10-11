<?php

declare(strict_types=1);

namespace App\Infrastructure\Tricks\Repository;

use App\Infrastructure\Tricks\Contracts\Repository\CategoriesRepositoryInterface;
use App\Infrastructure\Tricks\Entity\Category;
use App\Infrastructure\Tricks\Entity\Illustration;
use App\Infrastructure\Tricks\Entity\Trick;
use App\Infrastructure\Tricks\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Domain\Tricks\Entity\Trick as DomainTrick;
use Domain\Tricks\Gateway\TricksGateway;

class TricksRepository extends ServiceEntityRepository implements TricksGateway
{
    public function __construct(ManagerRegistry $registry, private CategoriesRepositoryInterface $categoriesRepository)
    {
        parent::__construct($registry, Trick::class);
    }

    public function getLastTricks(int $quantity): array
    {
        $query = $this->createQueryBuilder("t");

        $results = $query
            ->join('t.illustrations', 'illustrations')
            ->join('t.videos', 'v')
            ->orderBy('t.name', 'DESC')
            ->setMaxResults($quantity)
            ->getQuery()
            ->getResult();

        return array_map(function (Trick $trick) {
            return new DomainTrick(
                name: $trick->getName(),
                illustrations: $trick
                    ->getIllustrations()
                    ->map(fn (Illustration $illustration) => $illustration->getPath())
                    ->toArray(),
                category: $trick->getCategory()->getName(),
                thumbnail: $trick->getThumbnail()->getPath()
            );
        }, $results);
    }

    public function getTrickByName(string $name): DomainTrick
    {
        // TODO: Implement getTrickByName() method.
    }

    public function isNameAvailable(string $name): bool
    {
        $result = $this
            ->createQueryBuilder('t')
            ->where('t.name = :name')
            ->setParameter("name", $name)
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
        $trickEntity->setCategory($this->categoriesRepository->findOneBy(['name' => $trick->getCategory()]));

        $thumbnail = (new Illustration())->setTrick($trickEntity)->setPath($trick->getThumbnailUrl());
        $this->_em->persist($thumbnail);

        $trickEntity->setThumbnail($thumbnail);

        foreach ($trick->getIllustrationsPath() as $path)
        {
            $illustration = new Illustration();
            $illustration->setPath($path);
            $illustration->setTrick($trickEntity);

            $trickEntity->addIllustration($illustration);
            $this->_em->persist($illustration);
        }

        foreach ($trick->getVideoLinks() as $link)
        {
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
