<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Contracts\Factory;

use App\Infrastructure\Security\Entity\ActivationToken;
use App\Infrastructure\Security\Entity\AskNewPasswordToken;
use App\Infrastructure\Security\Entity\User;

interface JwtTokenFactoryInterface
{
    public function createActivationTokenForUser(User $user): ActivationToken;
    public function createNewPasswordTokenForUser(User $user): AskNewPasswordToken;
}
