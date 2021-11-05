<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ChangePassword;

use Domain\Security\Entity\Member;

class ChangePasswordResponse
{
    public function __construct(public Member $member)
    {
    }
}
