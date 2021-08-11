<?php

declare(strict_types=1);

namespace Domain\Security\Entity;

class Member
{
    protected string $email;
    protected string $username;
    protected string $password;

    public function __construct(string $email, string $username, string $password)
    {
        $this->email    = $email;
        $this->username = $username;
        $this->password = $password;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }
}
