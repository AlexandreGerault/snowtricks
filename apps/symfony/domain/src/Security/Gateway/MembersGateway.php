<?php

declare(strict_types=1);

namespace Domain\Security\Gateway;

use Domain\Security\Entity\Member;
use Domain\Security\Exceptions\UserNotFoundException;

interface MembersGateway
{
    /**
     * @param  string  $email
     * @throws UserNotFoundException
     *
     * @return Member
     */
    public function getMemberByEmail(string $email): Member;
}
