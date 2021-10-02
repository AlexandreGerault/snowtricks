<?php

declare(strict_types=1);

namespace Domain\Tricks\Gateway;

use Domain\Tricks\Entity\Trick;

interface TricksGateway
{
    /**
     * @return Trick[]
     */
    public function getLastTricks(int $quantity): array;

    public function getTrickByName(string $name): Trick;

    public function save(Trick $trick): void;
}
