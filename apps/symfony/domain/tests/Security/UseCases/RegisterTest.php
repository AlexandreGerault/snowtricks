<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\UseCases\Register\Register;
use Domain\Security\UseCases\Register\RegisterPresenterInterface;
use Domain\Security\UseCases\Register\RegisterRequest;
use Domain\Security\UseCases\Register\RegisterResponse;
use Domain\Tests\Security\Adapters\InMemoryMembersRepository;
use Domain\Tests\Security\Adapters\InMemoryNotifierAdapter;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase implements RegisterPresenterInterface
{
    private array $errors;
    private RegisterResponse $response;
    private Register $register;
    private InMemoryNotifierAdapter $notifier;

    public const EMAIL_ALREADY_USED = 'Cette adresse email est déjà utilisée.';
    public const USERNAME_ALREADY_USED = "Ce nom d'utilisateur est déjà pris.";

    protected function setUp(): void
    {
        parent::setUp();

        $this->errors = [];
        $repository = new InMemoryMembersRepository(
            [
                new Member(
                    'user@email',
                    'username',
                    'password'
                ),
            ]
        );
        $this->notifier = new InMemoryNotifierAdapter();

        $this->register = new Register($repository, $this->notifier);
    }

    public function testItCanRegisterAMember(): void
    {
        $request = new RegisterRequest('Username', 'email@email', 'password');

        $this->register->execute($request, $this);

        $this->assertEmpty($this->errors);
        $this->assertInstanceOf(Member::class, $this->response->createdMember);
        $this->assertCount(1, $this->notifier->notifications);
    }

    public function testItCannotRegisterAUserWithAnEmailThatIsAlreadyRegistered(): void
    {
        $request = new RegisterRequest('Username', 'user@email', 'password');

        $this->register->execute($request, $this);

        $this->assertCount(1, $this->errors);
        $this->assertContains(self::EMAIL_ALREADY_USED, $this->errors);
    }

    public function testItCannotRegisterAUserWithAUsernameThatIsAlreadyRegistered(): void
    {
        $request = new RegisterRequest('username', 'email@email', 'password');

        $this->register->execute($request, $this);

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

    public function presents(RegisterResponse $response): void
    {
        $this->response = $response;
    }
}
