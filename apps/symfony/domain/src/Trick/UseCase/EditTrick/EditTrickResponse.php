<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\EditTrick;

use Domain\Trick\Entity\Trick;

class EditTrickResponse
{
    public function __construct(public readonly Trick $trick)
    {
    }
}
