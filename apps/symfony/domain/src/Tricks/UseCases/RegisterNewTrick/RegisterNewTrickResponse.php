<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\RegisterNewTrick;

use Domain\Tricks\Entity\Trick;

class RegisterNewTrickResponse
{
    public function __construct(public ?Trick $trick)
    {
    }
}
