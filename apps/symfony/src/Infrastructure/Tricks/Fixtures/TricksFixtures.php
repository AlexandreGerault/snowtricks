<?php

declare(strict_types=1);

namespace App\Infrastructure\Tricks\Fixtures;

use App\Infrastructure\Tricks\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist((new Trick())->setName());
    }
}
