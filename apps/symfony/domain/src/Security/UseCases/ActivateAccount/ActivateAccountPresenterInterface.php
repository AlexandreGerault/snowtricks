<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ActivateAccount;

interface ActivateAccountPresenterInterface
{
    public function presents(ActivateAccountResponse $response): void;

    public function handleUserNotFound(): void;
}
