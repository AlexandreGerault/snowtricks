<?php

declare(strict_types=1);

namespace Domain\Tests\Security\Adapters;

use Domain\Security\Entity\Member;
use Domain\Security\Providers\AuthProviderInterface;

class AuthAdapter implements AuthProviderInterface
{
    public function check(Member $member, string $plainPassword): bool
    {
        return password_verify($plainPassword, $member->password());
    }
}
