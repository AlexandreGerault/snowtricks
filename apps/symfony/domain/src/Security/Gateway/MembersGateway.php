<?php

declare(strict_types=1);

namespace Domain\Security\Gateway;

use Domain\Security\Entity\Member;
use Domain\Security\Exceptions\UserNotFoundException;

interface MembersGateway
{
    /**
     * @throws UserNotFoundException
     */
    public function getMemberByEmail(string $email): Member;

    public function checkEmailIsFree(string $email): bool;

    public function checkUsernameIsFree(string $username): bool;

    public function register(Member $member): void;

    public function updateMember(Member $member): void;
}
