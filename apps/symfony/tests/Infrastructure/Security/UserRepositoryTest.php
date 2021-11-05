<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Security;

use App\Infrastructure\Security\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    /** @test */
    public function itCannotUpgradeThePasswordOfNonUserEntity(): void
    {
        self::bootKernel();
        $this->expectException(UnsupportedUserException::class);

        /** @var UserRepository $repository */
        $repository = $this->getContainer()->get(UserRepository::class);

        $nonUser = new class() implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return '';
            }
        };

        $repository->upgradePassword($nonUser, '');
    }
}
