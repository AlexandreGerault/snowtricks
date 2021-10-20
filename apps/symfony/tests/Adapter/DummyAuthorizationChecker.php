<?php

declare(strict_types=1);

namespace App\Tests\Adapter;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DummyAuthorizationChecker implements AuthorizationCheckerInterface
{
    public function __construct(public bool $isGranted = false)
    {
    }

    public function isGranted($attribute, $subject = null): bool
    {
        return $this->isGranted;
    }
}
