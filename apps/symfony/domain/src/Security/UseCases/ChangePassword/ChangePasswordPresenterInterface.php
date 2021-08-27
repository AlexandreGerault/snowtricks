<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ChangePassword;

interface ChangePasswordPresenterInterface
{
    public function presents(ChangePasswordResponse $response): void;

    public function handleUserNotFound(): void;
}
