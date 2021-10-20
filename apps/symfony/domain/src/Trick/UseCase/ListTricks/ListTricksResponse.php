<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\ListTricks;

use Domain\Trick\Entity\TrickOverview;

class ListTricksResponse
{
    /**
     * @var TrickOverview[]
     */
    public array $tricks;
}
