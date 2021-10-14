<?php

declare(strict_types=1);

namespace Domain\Tests\Security\UseCases;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\Providers\AuthProviderInterface;
use Domain\Security\UseCases\Login\Login;
use Domain\Security\UseCases\Login\LoginPresenterInterface;
use Domain\Security\UseCases\Login\LoginRequest;
use Domain\Security\UseCases\Login\LoginResponse;
use Domain\Tests\Security\Adapters\AuthAdapter;
use Domain\Tests\Security\Adapters\InMemoryMembersRepository;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase implements LoginPresenterInterface
{
    private LoginResponse $response;
    private MembersGateway $repository;
    private AuthProviderInterface $auth;

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
        $this->auth = new AuthAdapter();
    }

    public function test_the_response_has_a_member_if_correct_credentials_provided()
    {
        $request = new LoginRequest("user@email", "password");

        $login = new Login($this->repository, $this->auth);
        $login->execute($request, $this);

        $this->assertEmpty($this->response->getErrors());
        $this->assertInstanceOf(Member::class, $this->response->getMember());
    }

    public function test_the_response_has_a_wrong_credentials_errors_if_wrong_credentials_are_provided()
    {
        $request = new LoginRequest("user@email", "wrong");

        $login = new Login($this->repository, $this->auth);
        $login->execute($request, $this);

        $this->assertNotEmpty($this->response->getErrors());
        $this->assertContains("WrongCredentials", $this->response->getErrors());
    }

    public function test_the_response_has_a_user_not_found_error_if_no_user_matches_the_provided_email()
    {
        $request = new LoginRequest("nouserfound@email", "password");

        $login = new Login($this->repository, $this->auth);
        $login->execute($request, $this);

        $this->assertNotEmpty($this->response->getErrors());
        $this->assertContains("UserNotFound", $this->response->getErrors());
    }

    public function presents(LoginResponse $response): void
    {
        $this->response = $response;
    }
}
