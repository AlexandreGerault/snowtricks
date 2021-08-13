<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Register;

class RegisterRequest
{
    public function __construct(
        public string $username = "",
        public string $email = "",
        public string $password = "",
    ) {
    }
}
