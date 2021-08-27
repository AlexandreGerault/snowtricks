<?php

declare(strict_types=1);

namespace Domain\Security\UseCases\ChangePassword;

use Domain\Security\Entity\Member;
use Domain\Security\Exceptions\UserNotFoundException;
use Domain\Security\Gateway\MembersGateway;

class ChangePassword
{
    public function __construct(private MembersGateway $membersGateway)
    {
    }

    public function execute(ChangePasswordRequest $request, ChangePasswordPresenterInterface $presenter)
    {
        $response = new ChangePasswordResponse();

        try {
            $member = $this->membersGateway->getMemberByEmail($request->email);
            $member->changePassword($request->password);
            $this->membersGateway->updateMember($member);

            $response->member = $member;
        } catch (UserNotFoundException $e) {
            $presenter->handleUserNotFound();
            return;
        }

        $presenter->presents($response);
    }
}
