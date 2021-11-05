<?php

declare(strict_types=1);

namespace App\Tests\Functional\Security\Fixture;

use App\Infrastructure\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadHashedPasswordUserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setActive(true);
        $user->setEmail('user@email.fr');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $user->setUsername('user');

        $manager->persist($user);
        $manager->flush();
    }
}
