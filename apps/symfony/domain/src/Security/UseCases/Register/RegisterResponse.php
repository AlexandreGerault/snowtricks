<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Register;

use Domain\Security\Entity\Member;

class RegisterResponse
{
    public Member $createdMember;

    public function __construct(Member $createdMember)
    {
        $this->createdMember = $createdMember;
    }
}
