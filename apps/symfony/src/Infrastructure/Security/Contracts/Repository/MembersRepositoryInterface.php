<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Contracts\Repository;

use App\Infrastructure\Security\Entity\User;
use Domain\Security\Gateway\MembersGateway;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface MembersRepositoryInterface extends MembersGateway
{
    public function getUserByEmail(string $email): User;

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void;
}
