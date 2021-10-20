<?php

declare(strict_types=1);

namespace App\Tests\Application\Trick\Fixture;

use App\Infrastructure\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadUserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setActive(true);
        $user->setEmail("user@email");
        $user->setPassword("password");
        $user->setUsername("user");

        $manager->persist($user);
        $manager->flush();
    }
}
