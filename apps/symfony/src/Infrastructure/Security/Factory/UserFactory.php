<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Factory;

use App\Infrastructure\Security\Contracts\Factory\UserFactoryInterface;
use App\Infrastructure\Security\Entity\User;
use Domain\Security\Entity\Member;

class UserFactory implements UserFactoryInterface
{
    public function createFromMember(Member $member): User
    {
        $user = new User();

        $user->setEmail($member->email());
        $user->setUsername($member->username());
        $user->setPassword($member->password());
        $user->setActive($member->isActive());

        return $user;
    }
}
