<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\UseCases\ChangePassword\ChangePassword;
use Domain\Security\UseCases\ChangePassword\ChangePasswordPresenterInterface;
use Domain\Security\UseCases\ChangePassword\ChangePasswordRequest;
use Domain\Security\UseCases\ChangePassword\ChangePasswordResponse;
use Domain\Tests\Security\Adapters\InMemoryMembersRepository;
use PHPUnit\Framework\TestCase;

class ChangePasswordTest extends TestCase implements ChangePasswordPresenterInterface
{
    private MembersGateway $repository;
    private ChangePasswordResponse $response;

    private array $errors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryMembersRepository(
            [
                new Member(
                    "user@email",
                    "username",
                    "password"
                )
            ]
        );
        $this->errors = [];
    }

    public function test_it_can_change_the_password_of_a_member()
    {
        $request = new ChangePasswordRequest();
        $request->email = "user@email";
        $request->password = "newPassword";
        $useCase = new ChangePassword($this->repository);

        $useCase->execute($request, $this);

        $this->assertInstanceOf(Member::class, $this->response->member);
        $this->assertEquals("user@email", $this->response->member->email());
        $this->assertEquals("username", $this->response->member->username());
        $this->assertEquals("newPassword", $this->response->member?->password());
    }

    public function test_it_has_errors_if_no_user_found()
    {
        $request = new ChangePasswordRequest();
        $request->email = "not@found";
        $useCase = new ChangePassword($this->repository);

        $useCase->execute($request, $this);

        $this->assertCount(1, $this->errors);
    }

    public function presents(ChangePasswordResponse $response): void
    {
        $this->response = $response;
    }

    public function handleUserNotFound(): void
    {
        $this->errors[] = "User not found";
    }
}
