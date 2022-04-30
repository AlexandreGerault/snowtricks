<?php

namespace Domain\Tests\Trick\Factory;

use Domain\Trick\Entity\Trick;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

class TrickFactory
{
    public AbstractUid $uuid;
    public string $name = 'Trick';
    public string $category = 'Category';
    public string $description = 'Description';
    public string $thumbnailUrl = 'Thumbnail';
    public array $illustrations = ['illustration1', 'illustration 2'];
    public array $videos = ['videoLink1'];

    public static function new(): TrickFactory
    {
        $new = new self();
        $new->uuid = Uuid::v4();
        return $new;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function category(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function thumbnail(string $thumbnail): self
    {
        $this->thumbnailUrl = $thumbnail;

        return $this;
    }

    public function illustrations(array $illustrations): self
    {
        $this->illustrations = $illustrations;

        return $this;
    }

    public function videos(array $videos): self
    {
        $this->videos = $videos;

        return $this;
    }

    public function create(): Trick
    {
        return new Trick(
            $this->uuid,
            $this->name,
            $this->illustrations,
            $this->description,
            $this->category,
            $this->videos,
            $this->thumbnailUrl
        );
    }

    public function uuid(AbstractUid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
