<?php

namespace App\Infrastructure\Security\Authenticators;

use App\Infrastructure\Security\Contracts\Repository\MembersRepositoryInterface;
use Domain\Security\Entity\Member;
use Domain\Security\Providers\AuthProviderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthenticationChecker implements AuthProviderInterface
{
    public function __construct(
        private MembersRepositoryInterface $repository,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function check(Member $member, string $plainPassword): bool
    {
        $user = $this->repository->getUserByEmail($member->email());

        return $this->hasher->isPasswordValid($user, $plainPassword);
    }
}
