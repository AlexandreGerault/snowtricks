<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\EditTrick;

interface EditTrickPresenterInterface
{
    public function presents(EditTrickResponse $response): void;
}
