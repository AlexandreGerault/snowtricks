<?php

declare(strict_types=1);

namespace Domain\Security\Providers;

use Domain\Security\Entity\Member;

interface NotificationProviderInterface
{
    public function sendRegistrationNotification(Member $member): void;

    public function sendNewPasswordRequest(Member $member): void;
}
