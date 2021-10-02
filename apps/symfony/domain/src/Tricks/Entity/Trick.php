<?php

declare(strict_types=1);

namespace Domain\Tricks\Entity;

class Trick
{
    private string $imageUrl;

    public function __construct(
        private string $name,
        private array $illustrations,
        private ?string $description = null,
        private ?string $category = null,
        private array $videos = []
    ) {
        if ($name === "") {
            throw new \InvalidArgumentException("Name cannot be empty");
        }

        if ($description === "") {
            throw new \InvalidArgumentException("Description cannot be empty");
        }

        if ($this->category === "") {
            throw new \InvalidArgumentException("Category cannot be empty");
        }

        if (count($this->illustrations) < 1) {
            throw new \InvalidArgumentException("Must at least have 1 illustration");
        }

        $this->imageUrl = current($this->illustrations);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
