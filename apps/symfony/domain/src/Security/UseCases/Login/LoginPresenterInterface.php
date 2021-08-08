<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Login;

interface LoginPresenterInterface
{
    public function presents(LoginResponse $response): void;
}
