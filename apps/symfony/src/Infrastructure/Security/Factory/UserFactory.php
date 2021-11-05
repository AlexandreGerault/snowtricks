<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Factory;

use App\Infrastructure\Security\Contracts\Factory\UserFactoryInterface;
use App\Infrastructure\Security\Entity\User;
use Domain\Security\Entity\Member;

class UserFactory implements UserFactoryInterface
{
    public function updateToMember(User $user, Member $member): User
    {
        return ($user)
            ->setEmail($member->email())
            ->setUsername($member->username())
            ->setPassword($member->password())
            ->setActive($member->isActive());
    }

    public function createFromMember(Member $member): User
    {
        return (new User())
            ->setEmail($member->email())
            ->setUsername($member->username())
            ->setPassword($member->password())
            ->setActive($member->isActive());
    }

    public function createMember(User $user): Member
    {
        return new Member(
            $user->getEmail() ?? '',
            $user->getUsername(),
            $user->getPassword(),
            $user->isActive()
        );
    }
}
