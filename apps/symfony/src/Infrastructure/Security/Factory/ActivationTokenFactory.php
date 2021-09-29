<?php

namespace App\Infrastructure\Security\Factory;

use App\Infrastructure\Security\Contracts\Factory\ActivationTokenFactoryInterface;
use App\Infrastructure\Security\Entity\ActivationToken;
use App\Infrastructure\Security\Entity\User;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;

class ActivationTokenFactory implements ActivationTokenFactoryInterface
{
    public function __construct(private Configuration $configuration)
    {
    }

    public function createForUser(User $user): ActivationToken
    {
        $token = new ActivationToken();

        $token->setUser($user);
        $token->setToken($this->generateToken($user->getEmail()));

        return $token;
    }

    private function generateToken(string $userId): string
    {
        $now = new DateTimeImmutable();

        $token = $this
            ->configuration
            ->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 day'))
            ->withClaim("uid", $userId)
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }
}
