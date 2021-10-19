<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ActivateAccount;

use Domain\Security\Exceptions\UserNotFoundException;
use Domain\Security\Gateway\MembersGateway;

class ActivateAccount
{
    public function __construct(private MembersGateway $gateway)
    {
    }

    public function execute(ActivateAccountRequest $request, ActivateAccountPresenterInterface $presenter): void
    {
        try {
            $member = $this->gateway->getMemberByEmail($request->email);
        } catch (UserNotFoundException $e) {
            $presenter->handleUserNotFound();

            return;
        }
        $member->activate();
        $this->gateway->updateMember($member);

        $response = new ActivateAccountResponse($member);

        $presenter->presents($response);
    }
}
