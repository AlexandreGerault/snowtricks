<?php

declare(strict_types=1);

namespace Domain\Security\Providers;

use Domain\Security\Entity\Member;

interface AuthProviderInterface
{
    public function check(Member $member, string $plainPassword): bool;
}
