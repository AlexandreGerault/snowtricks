<?php

namespace App\UserInterface\Trick\DTO;

use App\Infrastructure\Trick\Entity\Category;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterNewTrickFormModel
{
    #[Assert\NotBlank]
    public Category $category;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    public string $description;

    #[Assert\Image(maxSize: '1024k')]
    public UploadedFile $thumbnail;

    /**
     * @var File[]
     */
    #[Assert\Count(min: 1)]
    public array $illustrations;

    /**
     * @var string[]
     */
    #[Assert\Count(min: 1)]
    public array $videos;

    public function __construct()
    {
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
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

    public function getThumbnail(): UploadedFile
    {
        return $this->thumbnail;
    }

    public function setThumbnail(UploadedFile $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return File[]
     */
    public function getIllustrations(): array
    {
        return $this->illustrations;
    }

    /**
     * @param  File[]  $illustrations
     */
    public function setIllustrations(array $illustrations): void
    {
        $this->illustrations = $illustrations;
    }

    /**
     * @return string[]
     */
    public function getVideos(): array
    {
        return $this->videos;
    }

    /**
     * @param  string[]  $videos
     */
    public function setVideos(array $videos): void
    {
        $this->videos = $videos;
    }
}
