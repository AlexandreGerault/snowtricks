<?php

declare(strict_types=1);

namespace App\UserInterface\Security\ViewModels;

use Domain\Security\Entity\Member;

class LoginViewModel
{
    public function __construct(
        public array $errors = [],
        public ?Member $member = null
    ) {
    }
}
