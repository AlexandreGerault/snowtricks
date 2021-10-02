<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ChangePassword;

class ChangePasswordRequest
{
    public string $email = "";
    public string $password = "";
}
