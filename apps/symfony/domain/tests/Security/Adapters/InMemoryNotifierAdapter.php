<?php

declare(strict_types=1);

namespace Domain\Tests\Security\Adapters;

use Domain\Security\Entity\Member;
use Domain\Security\Providers\NotificationProviderInterface;

class InMemoryNotifierAdapter implements NotificationProviderInterface
{
    public function __construct(public array $notifications = [])
    {
    }

    public function sendRegistrationNotification(Member $member): void
    {
        $this->notifications[] = "Activate account";
    }

    public function sendNewPasswordRequest(Member $member): void
    {
        $this->notifications[] = "Ask new password";
    }
}
