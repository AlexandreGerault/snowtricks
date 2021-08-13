<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Register;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;

class Register
{
    public function __construct(private MembersGateway $gateway)
    {
    }

    public function execute(RegisterRequest $request, RegisterPresenterInterface $presenter)
    {
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;

        if (! $this->gateway->checkEmailIsFree($email)) {
            $presenter->handleEmailAlreadyInUse();
            return;
        }

        if (! $this->gateway->checkUsernameIsFree($username)) {
            $presenter->handleUsernameAlreadyInUse();
            return;
        }

        $member = new Member($email, $username, $password);
        $this->gateway->register($member);
    }
}
