<?php

declare(strict_types=1);

namespace Domain\Trick\Gateway;

use Domain\Trick\Entity\Trick;
use Domain\Trick\Entity\TrickOverview;

interface TrickGateway
{
    /**
     * @return TrickOverview[]
     */
    public function getLastTricksOverviews(int $quantity): array;

    public function getTrickByName(string $name): Trick;

    public function isNameAvailable(string $name): bool;

    public function save(Trick $trick): void;
}
