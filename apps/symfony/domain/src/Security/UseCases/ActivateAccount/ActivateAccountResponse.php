<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ActivateAccount;

use Domain\Security\Entity\Member;

class ActivateAccountResponse
{
    public function __construct(public Member $member)
    {
    }
}
