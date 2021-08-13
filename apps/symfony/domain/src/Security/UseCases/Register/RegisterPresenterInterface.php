<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Register;

interface RegisterPresenterInterface
{
    public function handleEmailAlreadyInUse(): void;

    public function handleUsernameAlreadyInUse(): void;

    public function handlePasswordsMismatch(): void;
}
