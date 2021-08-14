<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Register;

class RegisterRequest
{
    public function __construct(
        public ?string $username = null,
        public ?string $email = null,
        public ?string $password = null,
    ) {
    }
}
