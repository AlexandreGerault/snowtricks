<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\Register;

use Domain\Security\Entity\Member;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\Providers\NotificationProviderInterface;

class Register
{
    public function __construct(private MembersGateway $gateway, private NotificationProviderInterface $notifier)
    {
    }

    public function execute(RegisterRequest $request, RegisterPresenterInterface $presenter): void
    {
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;

        if (!$this->gateway->checkEmailIsFree($email)) {
            $presenter->handleEmailAlreadyInUse();

            return;
        }

        if (!$this->gateway->checkUsernameIsFree($username)) {
            $presenter->handleUsernameAlreadyInUse();

            return;
        }

        $member = new Member($email, $username, $password);
        $this->gateway->register($member);
        $this->notifier->sendRegistrationNotification($member);

        $presenter->presents(new RegisterResponse($member));
    }
}
