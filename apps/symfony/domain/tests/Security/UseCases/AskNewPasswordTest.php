<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\Providers\NotificationProviderInterface;
use Domain\Security\UseCases\AskNewPassword\AskNewPassword;
use Domain\Security\UseCases\AskNewPassword\AskNewPasswordPresenterInterface;
use Domain\Security\UseCases\AskNewPassword\AskNewPasswordRequest;
use Domain\Security\UseCases\AskNewPassword\AskNewPasswordResponse;
use Domain\Tests\Security\Adapters\InMemoryMembersRepository;
use Domain\Tests\Security\Adapters\InMemoryNotifierAdapter;
use PHPUnit\Framework\TestCase;

class AskNewPasswordTest extends TestCase implements AskNewPasswordPresenterInterface
{
    private AskNewPasswordResponse $response;
    private NotificationProviderInterface $notifier;
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
                    "password"
                )
            ]
        );
        $this->errors = [];
        $this->notifier = new InMemoryNotifierAdapter();
    }

    public function test_it_creates_a_notification_when_user_ask_a_new_password()
    {
        $useCase = new AskNewPassword($this->repository, $this->notifier);

        $request = new AskNewPasswordRequest("user@email");
        $useCase->execute($request, $this);

        $this->assertInstanceOf(Member::class, $this->response->member);
        $this->assertCount(1, $this->notifier->notifications);
    }

    public function test_it_creates_an_error_if_user_is_not_found()
    {
        $useCase = new AskNewPassword($this->repository, $this->notifier);

        $request = new AskNewPasswordRequest("not-found@email");
        $useCase->execute($request, $this);

        $this->assertCount(1, $this->errors);
    }

    public function presents(AskNewPasswordResponse $response): void
    {
        $this->response = $response;
    }

    public function handleUserNotFound(): void
    {
        $this->errors[] = "User not found";
    }
}
