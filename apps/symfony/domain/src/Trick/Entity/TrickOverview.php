<?php

declare(strict_types=1);

namespace Domain\Trick\Entity;

use InvalidArgumentException;

class TrickOverview
{
    public function __construct(
        private string $name,
        private string $category,
        private string $thumbnail = ''
    ) {
        if ('' === $name) {
            throw new InvalidArgumentException('Name cannot be empty');
        }

        if ('' === $thumbnail) {
            throw new InvalidArgumentException('Thumbnail cannot be empty');
        }

        if ('' === $category) {
            throw new InvalidArgumentException('Category cannot be empty');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnail;
    }
}
