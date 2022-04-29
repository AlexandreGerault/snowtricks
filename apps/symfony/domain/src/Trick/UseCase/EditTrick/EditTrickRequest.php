<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\EditTrick;

class EditTrickRequest
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly string $description,
    ) {
    }
}
