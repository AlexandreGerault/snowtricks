<?php

declare(strict_types=1);

namespace App\UserInterface\Security\ViewModels;

use Domain\Security\Entity\Member;

class HtmlLoginViewModel
{
    public function __construct(
        public array $errors = [],
        public ?Member $member = null
    ) {
    }
}
