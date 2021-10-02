<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\AskNewPassword;

use Domain\Security\Entity\Member;

class AskNewPasswordResponse
{
    public Member $member;
}
