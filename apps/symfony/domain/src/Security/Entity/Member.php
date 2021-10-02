<?php

declare(strict_types=1);

namespace Domain\Security\Entity;

class Member
{
    protected string $email;
    protected string $username;
    protected string $password;
    protected bool $active;

    public function __construct(string $email, string $username, string $password, bool $active = false)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->active = $active;
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

    public function activate(): void
    {
        $this->active = true;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function changePassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }
}
