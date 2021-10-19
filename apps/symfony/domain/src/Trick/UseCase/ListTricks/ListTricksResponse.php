<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\ListTricks;

use Domain\Trick\Entity\Trick;

class ListTricksResponse
{
    /**
     * @var Trick[]
     */
    public array $tricks;
}
