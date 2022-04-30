<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\DTO;

use App\Infrastructure\Trick\Entity\Category;
use App\Infrastructure\Trick\Entity\Trick;
use App\Infrastructure\Trick\Entity\Video;
use Symfony\Component\Validator\Constraints as Assert;


class EditTrickFormModel
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    /**
     * @var string[]
     */
    #[Assert\Count(min: 1)]
    public array $videos;

    #[Assert\NotBlank]
    public Category $category;

    public function __construct(Trick $trick)
    {
        $this->name = $trick->getName();
        $this->description = $trick->getDescription();
        $this->category = $trick->getCategory();
        $this->videos = $trick->getVideoLinks()->map(function (Video $video) {
            return $video->getLink();
        })->toArray();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param string[] $videos
     */
    public function setVideos(array $videos): void
    {
        $this->videos = $videos;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
