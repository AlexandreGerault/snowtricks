<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\RegisterNewTrick;

interface RegisterNewTrickPresenterInterface
{
    public function presents(RegisterNewTrickResponse $response): void;
}
