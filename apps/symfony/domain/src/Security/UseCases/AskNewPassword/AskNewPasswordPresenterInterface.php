<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\AskNewPassword;

interface AskNewPasswordPresenterInterface
{
    public function presents(AskNewPasswordResponse $response): void;

    public function handleUserNotFound(): void;
}
