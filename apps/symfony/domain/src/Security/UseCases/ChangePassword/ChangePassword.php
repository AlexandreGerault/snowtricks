<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ChangePassword;

use Domain\Security\Exceptions\UserNotFoundException;
use Domain\Security\Gateway\MembersGateway;

class ChangePassword
{
    public function __construct(private MembersGateway $membersGateway)
    {
    }

    public function execute(ChangePasswordRequest $request, ChangePasswordPresenterInterface $presenter): void
    {
        try {
            $member = $this->membersGateway->getMemberByEmail($request->email);
            $this->membersGateway->updatePassword($member, $request->password);
        } catch (UserNotFoundException $e) {
            $presenter->handleUserNotFound();

            return;
        }

        $response = new ChangePasswordResponse($member);
        $presenter->presents($response);
    }
}
