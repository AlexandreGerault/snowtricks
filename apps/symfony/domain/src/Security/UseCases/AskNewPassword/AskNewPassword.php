<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\AskNewPassword;

use Domain\Security\Exceptions\UserNotFoundException;
use Domain\Security\Gateway\MembersGateway;
use Domain\Security\Providers\NotificationProviderInterface;

class AskNewPassword
{
    public function __construct(private MembersGateway $gateway, private NotificationProviderInterface $notifier)
    {
    }

    public function execute(AskNewPasswordRequest $request, AskNewPasswordPresenterInterface $presenter): void
    {
        try {
            $member = $this->gateway->getMemberByEmail($request->email);
        } catch (UserNotFoundException $e) {
            $presenter->handleUserNotFound();

            return;
        }

        $this->notifier->sendNewPasswordRequest($member);

        $response = new AskNewPasswordResponse();
        $response->member = $member;

        $presenter->presents($response);
    }
}
