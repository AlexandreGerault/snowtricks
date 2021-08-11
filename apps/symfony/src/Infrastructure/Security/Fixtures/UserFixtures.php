<?php

namespace App\Infrastructure\Security\Fixtures;

use App\Infrastructure\Security\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Alexandre');
        $user->setEmail('alexandre@hexium.io');
        $user->setPassword($this->hasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $manager->flush();
    }
}
