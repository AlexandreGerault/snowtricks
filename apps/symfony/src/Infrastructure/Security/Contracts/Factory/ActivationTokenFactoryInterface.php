<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Contracts\Factory;

use App\Infrastructure\Security\Entity\ActivationToken;
use App\Infrastructure\Security\Entity\User;

interface ActivationTokenFactoryInterface
{
    public function createForUser(User $user): ActivationToken;
}
