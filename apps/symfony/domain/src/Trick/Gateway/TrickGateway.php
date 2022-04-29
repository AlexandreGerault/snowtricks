<?php

declare(strict_types=1);

namespace Domain\Trick\Gateway;

use Domain\Trick\Entity\Trick;
use Domain\Trick\Entity\TrickOverview;
use Symfony\Component\Uid\Uuid;

interface TrickGateway
{
    public function findByUuid(Uuid $uuid): Trick;

    /**
     * @return TrickOverview[]
     */
    public function getLastTricksOverviews(int $quantity): array;

    public function isNameAvailable(string $name): bool;

    public function save(Trick $trick): void;
}
