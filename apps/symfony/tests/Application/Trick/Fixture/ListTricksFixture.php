<?php

declare(strict_types=1);

namespace App\Tests\Application\Trick\Fixture;

use App\Infrastructure\Trick\Entity\Category;
use App\Infrastructure\Trick\Entity\Illustration;
use App\Infrastructure\Trick\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ListTricksFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = (new Category())->setName('Catégorie');
        $manager->persist($category);

        for ($i = 0; $i < 15; ++$i) {
            $trick = new Trick();
            $trick->setName("Trick {$i}");
            $trick->setCategory($category);
            $trick->setDescription('Description');

            $thumbnail = (new Illustration())->setPath('something');
            $thumbnail->setTrick($trick);
            $manager->persist($thumbnail);

            $trick->setThumbnail($thumbnail);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
