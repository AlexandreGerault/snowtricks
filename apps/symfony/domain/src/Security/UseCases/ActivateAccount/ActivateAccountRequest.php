<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ActivateAccount;

class ActivateAccountRequest
{
    public function __construct(public string $email = '')
    {
    }
}
