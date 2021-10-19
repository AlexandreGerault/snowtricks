<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\ListTricks;

interface ListTricksPresenterInterface
{
    public function presents(ListTricksResponse $response): void;
}
