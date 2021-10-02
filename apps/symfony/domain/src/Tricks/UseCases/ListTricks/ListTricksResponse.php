<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\ListTricks;

use Domain\Tricks\Entity\Trick;

class ListTricksResponse
{
    /**
     * @var Trick[]
     */
    public array $tricks;
}
