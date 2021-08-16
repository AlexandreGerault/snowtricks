<?php

namespace App\Infrastructure\Security\Factory;

use App\Infrastructure\Security\Contracts\Factory\ActivationTokenFactoryInterface;
use App\Infrastructure\Security\Entity\ActivationToken;
use App\Infrastructure\Security\Entity\User;

class ActivationTokenFactory implements ActivationTokenFactoryInterface
{
    private const TOKEN_BYTES_LENGTH = 16;

    public function createForUser(User $user): ActivationToken
    {
        $token = new ActivationToken();

        $token->setUser($user);
        $token->setToken($this->generateToken());

        return $token;
    }

    private function generateToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(self::TOKEN_BYTES_LENGTH));
    }
}
