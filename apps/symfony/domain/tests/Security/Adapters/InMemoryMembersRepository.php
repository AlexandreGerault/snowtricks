<?php

declare(strict_types=1);

namespace Domain\Tests\Security\Adapters;

use Domain\Security\Entity\Member;
use Domain\Security\Exceptions\UserNotFoundException;
use Domain\Security\Gateway\MembersGateway;

class InMemoryMembersRepository implements MembersGateway
{
    protected array $members = [];

    /**
     * @param  Member[]  $members
     */
    public function __construct(array $members = [])
    {
        foreach ($members as $member) {
            $this->members[$member->email()] = $member;
        }
    }

    public function getMemberByEmail(string $email): Member
    {
        return $this->members[$email] ?? throw new UserNotFoundException();
    }

    public function checkEmailIsFree(string $email): bool
    {
        return ! array_key_exists($email, $this->members);
    }

    public function checkUsernameIsFree(string $username): bool
    {
        return count(array_filter($this->members, fn (Member $member) => $member->username() === $username)) === 0;
    }

    public function register(Member $member): void
    {
        $this->members[$member->email()] = $member;
    }
}
