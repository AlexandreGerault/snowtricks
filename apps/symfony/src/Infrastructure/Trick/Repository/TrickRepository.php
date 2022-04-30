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
use Symfony\Component\Uid\Uuid;

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
                uuid: $trick->getUuid(),
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

        $trickEntity->setUuid($trick->getId());
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

    public function findByUuid(Uuid $uuid): DomainTrick
    {
        /** @var Trick $trick */
        $trick = $this->findOneBy(['uuid' => $uuid]);

        return new DomainTrick(
            $uuid,
            $trick->getName(),
            $trick->getIllustrationPaths(),
            $trick->getDescription(),
            $trick->getCategory()->getName(),
            $trick->getVideoLinks()->toArray(),
            $trick->getThumbnail()->getPath()
        );
    }

    public function update(DomainTrick $trick): void
    {
        $doctrineTrick = $this->findOneBy(['uuid' => $trick->getId()->toRfc4122()]);
        $originalVideoLinks = $doctrineTrick->getVideoLinks()->map(fn (Video $video) => $video->getLink())->toArray();

        $doctrineTrick->setName($trick->getName());
        $doctrineTrick->setDescription($trick->getDescription());
        $doctrineTrick->setCategory(
            $this->categoryRepository->findOneBy(['name' => $trick->getCategory()])
            ?? throw new \RuntimeException('The category is invalid')
        );

        foreach (array_diff($trick->getVideoLinks(), $originalVideoLinks) as $videoLink) {
            $video = (new Video())->setLink($videoLink)->setTrick($doctrineTrick);
            $doctrineTrick->addVideo($video);
            $this->_em->persist($video);
        }

        foreach (array_diff($originalVideoLinks, $trick->getVideoLinks()) as $videoLink) {
            $this->_em->remove($doctrineTrick->removeVideo($videoLink));
        }

        $this->_em->persist($doctrineTrick);
        $this->_em->flush();
    }
}
