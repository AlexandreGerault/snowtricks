<?php

declare(strict_types=1);

namespace App\Tests\Functional\Trick\Fixture;

use App\Infrastructure\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadUserFixture extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setActive(true);
        $user->setEmail('user@email.fr');
        $user->setPassword('password');
        $user->setUsername('user');

        $manager->persist($user);
        $manager->flush();
    }
}
