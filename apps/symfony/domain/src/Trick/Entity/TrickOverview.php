<?php

declare(strict_types=1);

namespace Domain\Trick\Entity;

class TrickOverview
{
    public function __construct(
        private string $name,
        private string $category,
        private string $thumbnail = ''
    ) {
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
