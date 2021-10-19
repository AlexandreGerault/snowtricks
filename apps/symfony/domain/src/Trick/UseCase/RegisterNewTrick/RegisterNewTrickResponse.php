<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\RegisterNewTrick;

use Domain\Trick\Entity\Trick;

class RegisterNewTrickResponse
{
    public function __construct(public ?Trick $trick)
    {
    }
}
