<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
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
    private InMemoryNotifierAdapter $notifier;
    private MembersGateway $repository;

    private array $errors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryMembersRepository(
            [
                new Member(
                    'user@email',
                    'username',
                    'password'
                ),
            ]
        );
        $this->errors = [];
        $this->notifier = new InMemoryNotifierAdapter();
    }

    public function testItCreatesANotificationWhenUserAskANewPassword(): void
    {
        $useCase = new AskNewPassword($this->repository, $this->notifier);

        $request = new AskNewPasswordRequest('user@email');
        $useCase->execute($request, $this);

        $this->assertInstanceOf(Member::class, $this->response->member);
        $this->assertCount(1, $this->notifier->notifications);
    }

    public function testItCreatesAnErrorIfUserIsNotFound(): void
    {
        $useCase = new AskNewPassword($this->repository, $this->notifier);

        $request = new AskNewPasswordRequest('not-found@email');
        $useCase->execute($request, $this);

        $this->assertCount(1, $this->errors);
    }

    public function presents(AskNewPasswordResponse $response): void
    {
        $this->response = $response;
    }

    public function handleUserNotFound(): void
    {
        $this->errors[] = 'User not found';
    }
}
