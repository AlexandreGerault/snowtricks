<?php

declare(strict_types=1);

namespace Domain\Trick\Entity;

use InvalidArgumentException;

class Trick
{
    /**
     * @param string $name
     * @param array  $illustrations
     * @param string $description
     * @param string $category
     * @param array  $videos
     * @param string $thumbnail
     */
    public function __construct(
        private string $name,
        private array $illustrations,
        private string $description,
        private string $category,
        private array $videos = [],
        private string $thumbnail = ''
    ) {
        if ('' === $name) {
            throw new InvalidArgumentException('Name cannot be empty');
        }

        if ('' === $thumbnail) {
            throw new InvalidArgumentException('Thumbnail cannot be empty');
        }

        if ('' === $description) {
            throw new InvalidArgumentException('Description cannot be empty');
        }

        if ('' === $category) {
            throw new InvalidArgumentException('Category cannot be empty');
        }

        if (count($this->illustrations) < 1) {
            throw new InvalidArgumentException('Must at least have 1 illustration');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnail;
    }

    /**
     * @return array<string>
     */
    public function getIllustrationsPath(): array
    {
        return $this->illustrations;
    }

    public function getVideoLinks(): array
    {
        return $this->videos;
    }
}
