<?php

namespace Domain\Tests\Trick\Adapter;

use Domain\Tests\Trick\Factory\TrickFactory;
use Domain\Trick\Entity\Trick;
use Domain\Trick\Gateway\TrickGateway;
use Symfony\Component\Uid\Uuid;

class InMemoryTrickRepository implements TrickGateway
{
    public function __construct(private array $tricks)
    {
    }

    public function getLastTricksOverviews(int $quantity): array
    {
        return array_slice($this->tricks, 0, min($quantity, count($this->tricks)));
    }

    public function getTrickByName(string $name): Trick
    {
        $matching = array_filter($this->tricks, function (Trick $trick) use ($name) {
            return $trick->getName() === $name;
        });

        if (0 === count($matching)) {
            throw new \LogicException('No trick with this name found');
        }

        if (count($matching) > 1) {
            throw new \LogicException('More than one trick found');
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

        return 0 === count($matching);
    }

    public function findByUuid(Uuid $uuid): Trick
    {
        return TrickFactory::new()->create();
    }
}
