<?php

namespace App\Infrastructure\Security\Factory;

use App\Infrastructure\Security\Contracts\Factory\JwtTokenFactoryInterface;
use App\Infrastructure\Security\Entity\ActivationToken;
use App\Infrastructure\Security\Entity\AskNewPasswordToken;
use App\Infrastructure\Security\Entity\User;
use DateTimeImmutable;
use Lcobucci\JWT\Configuration;

class JwtTokenFactory implements JwtTokenFactoryInterface
{
    public function __construct(private Configuration $configuration)
    {
    }

    public function createActivationTokenForUser(User $user): ActivationToken
    {
        $token = new ActivationToken();
        $userId = $user->getEmail() ?? throw new \InvalidArgumentException('User does not have a valid email address');

        $token->setUser($user);
        $token->setToken($this->generateToken($userId));

        return $token;
    }

    public function createNewPasswordTokenForUser(User $user): AskNewPasswordToken
    {
        $token = new AskNewPasswordToken();
        $userId = $user->getEmail() ?? throw new \InvalidArgumentException('User does not have a valid email address');

        $token->setUser($user);
        $token->setToken($this->generateToken($userId));

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
            ->withClaim('uid', $userId)
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }
}
