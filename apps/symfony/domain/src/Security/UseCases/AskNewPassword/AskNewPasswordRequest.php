<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\AskNewPassword;

class AskNewPasswordRequest
{
    public function __construct(public string $email = "")
    {
    }
}
