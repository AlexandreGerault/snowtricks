<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Login;

use Domain\Security\Entity\Member;

class LoginResponse
{
    private ?Member $member = null;

    private array $errors = [];

    public function __construct(Member $member = null)
    {
        $this->member = $member;
    }

    public function addError(string $message): self
    {
        $this->errors[] = $message;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
