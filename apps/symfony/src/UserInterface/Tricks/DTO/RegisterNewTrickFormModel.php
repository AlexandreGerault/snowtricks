<?php

namespace App\UserInterface\Tricks\DTO;

use App\Infrastructure\Tricks\Entity\Category;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterNewTrickFormModel
{
    #[Assert\NotBlank]
    public ?Category $category;

    #[Assert\NotBlank]
    public ?string $name;

    #[Assert\NotBlank]
    public ?string $description;

    #[Assert\Image(maxSize: '1024k')]
    public ?UploadedFile $thumbnail;

    /**
     * @var File[]
     * @Assert\All({
     *      @Assert\Image(maxSize='1024k')
     *  })
     */
    #[Assert\Count(min: 1)]
    public ?array $illustrations;

    /**
     * @var string[]
     * @Assert\All({
     *      @Assert\Url
     *  })
     */
    #[Assert\Count(min: 1)]
    public ?array $videos;

    public function __construct(
        ?Category $category = null,
        ?string $name = null,
        ?string $description = null,
        ?array $illustrations = null,
        ?array $videosUrls = null,
        ?UploadedFile $thumbnail = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
        $this->illustrations = $illustrations;
        $this->videos = $videosUrls;
        $this->thumbnail = $thumbnail;
    }
}
