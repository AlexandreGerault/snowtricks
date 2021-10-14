<?php

namespace Domain\Tests\Tricks\Adapters;

use Domain\Tricks\Entity\Trick;
use Domain\Tricks\Gateway\TricksGateway;

class InMemoryTricksRepository implements TricksGateway
{
    public function __construct(private array $tricks)
    {
    }

    public function getLastTricks(int $quantity): array
    {
        return array_slice($this->tricks, 0, min($quantity, count($this->tricks)));
    }

    public function getTrickByName(string $name): Trick
    {
        $matching = array_filter($this->tricks, function (Trick $trick) use ($name) {
            return $trick->getName() === $name;
        });

        if (count($matching) === 0) {
            throw new \LogicException("No trick with this name found");
        }

        if (count($matching) > 1) {
            throw new \LogicException("More than one trick found");
        }

        return current($matching);
    }

    public function save(Trick $trick): void
    {
        $this->tricks[] = $trick;
    }

    public function isNameAvailable(string $name): bool
    {
        $matching = array_filter($this->tricks, function (Trick $trick) use ($name) {
            return $trick->getName() === $name;
        });

        return count($matching) === 0;
    }
}
