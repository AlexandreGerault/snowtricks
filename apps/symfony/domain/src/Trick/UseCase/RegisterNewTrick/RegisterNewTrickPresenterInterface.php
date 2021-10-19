<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\RegisterNewTrick;

interface RegisterNewTrickPresenterInterface
{
    public function presents(RegisterNewTrickResponse $response): void;
}
