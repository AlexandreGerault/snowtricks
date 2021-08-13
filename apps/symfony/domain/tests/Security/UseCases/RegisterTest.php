<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\UseCases\Register\Register;
use Domain\Security\UseCases\Register\RegisterPresenterInterface;
use Domain\Security\UseCases\Register\RegisterRequest;
use Domain\Tests\Security\Adapters\InMemoryMembersRepository;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase implements RegisterPresenterInterface
{
    private array $errors;
    private MembersGateway $repository;

    public const EMAIL_ALREADY_USED = "Cette adresse email est déjà utilisée.";
    public const USERNAME_ALREADY_USED = "Ce nom d'utilisateur est déjà pris.";

    protected function setUp(): void
    {
        parent::setUp();

        $this->errors = [];
        $this->repository = new InMemoryMembersRepository(
        [
            new Member(
                "user@email",
                "username",
                password_hash("password", PASSWORD_ARGON2ID)
            )
        ]
    );
    }

    public function test_it_can_register_a_member()
    {
        $request = new RegisterRequest("Username", "email@email", "password", "password");

        $register = new Register($this->repository);
        $register->execute($request, $this);

        $registeredUser = $this->repository->getMemberByEmail("email@email");

        $this->assertEmpty($this->errors);
        $this->assertInstanceOf(Member::class, $registeredUser);
    }

    public function test_it_cannot_register_a_user_with_an_email_that_is_already_registered()
    {
        $request = new RegisterRequest("Username", "user@email", "password", "password");

        $register = new Register($this->repository);
        $register->execute($request, $this);

        $this->assertCount(1, $this->errors);
        $this->assertContains(self::EMAIL_ALREADY_USED, $this->errors);
    }

    public function test_it_cannot_register_a_user_with_a_username_that_is_already_registered()
    {
        $request = new RegisterRequest("username", "email@email", "password", "password");

        $register = new Register($this->repository);
        $register->execute($request, $this);

        $this->assertCount(1, $this->errors);
        $this->assertContains(self::USERNAME_ALREADY_USED, $this->errors);
    }

    public function handleEmailAlreadyInUse(): void
    {
        $this->errors[] = self::EMAIL_ALREADY_USED;
    }

    public function handleUsernameAlreadyInUse(): void
    {
        $this->errors[] = self::USERNAME_ALREADY_USED;
    }

    public function handlePasswordsMismatch(): void
    {
        $this->errors[] = "Les mots de passe entrés ne correspondent pas.";
    }
}
