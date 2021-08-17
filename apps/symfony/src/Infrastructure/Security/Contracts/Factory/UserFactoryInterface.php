<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Contracts\Factory;

use App\Infrastructure\Security\Entity\User;
use Domain\Security\Entity\Member;

interface UserFactoryInterface
{
    public function createFromMember(Member $member): User;
}
