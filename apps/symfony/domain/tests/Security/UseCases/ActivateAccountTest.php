<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\UseCases\ActivateAccount\ActivateAccount;
use Domain\Security\UseCases\ActivateAccount\ActivateAccountPresenterInterface;
use Domain\Security\UseCases\ActivateAccount\ActivateAccountRequest;
use Domain\Security\UseCases\ActivateAccount\ActivateAccountResponse;
use Domain\Tests\Security\Adapters\InMemoryMembersRepository;
use PHPUnit\Framework\TestCase;

class ActivateAccountTest extends TestCase implements ActivateAccountPresenterInterface
{
    private ActivateAccountResponse $response;
    private MembersGateway $repository;

    private array $errors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryMembersRepository(
            [
                new Member(
                    "user@email",
                    "username",
                    password_hash("password", PASSWORD_ARGON2ID)
                )
            ]
        );
        $this->errors = [];
    }

    public function test_a_user_is_activated()
    {
        $useCase = new ActivateAccount($this->repository);

        $activateAccountRequest = new ActivateAccountRequest("user@email");
        $useCase->execute($activateAccountRequest, $this);

        $this->assertInstanceOf(Member::class, $this->response->member);
        $this->assertTrue($this->response->member->isActive());
    }

    public function test_if_fails_if_email_does_not_match_a_user()
    {
        $useCase = new ActivateAccount($this->repository);

        $activateAccountRequest = new ActivateAccountRequest("does-not-exist@email");
        $useCase->execute($activateAccountRequest, $this);

        $this->assertCount(1, $this->errors);
    }

    public function presents(ActivateAccountResponse $response): void
    {
        $this->response = $response;
    }

    public function handleUserNotFound(): void
    {
        $this->errors[] = "User already exists";
    }
}