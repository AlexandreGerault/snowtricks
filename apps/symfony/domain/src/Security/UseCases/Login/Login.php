<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Login;

use Domain\Security\Exceptions\UserNotFoundException;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\Providers\AuthProviderInterface;

class Login
{
    public function __construct(private MembersGateway $membersGateway, private AuthProviderInterface $auth)
    {
    }

    public function execute(LoginRequest $request, LoginPresenterInterface $presenter): void
    {
        $email = $request->getEmail();
        $password = $request->getPassword();

        try {
            $member = $this->membersGateway->getMemberByEmail($email);
        } catch (UserNotFoundException) {
            $response = new LoginResponse();
            $response->addError('UserNotFound');
            $presenter->presents($response);

            return;
        }

        if (!$this->auth->check($member, $password)) {
            $response = new LoginResponse();
            $response->addError('WrongCredentials');
            $presenter->presents($response);

            return;
        }

        $response = new LoginResponse($member);
        $presenter->presents($response);
    }
}
