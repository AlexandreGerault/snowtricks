<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Entity;

use App\Infrastructure\Trick\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\Table(name: '`tricks`')]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private Category $category;

    /** @var Collection<int, Illustration> */
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Illustration::class)]
    private Collection $illustrations;

    /** @var Collection<int, Video> */
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class)]
    private Collection $videos;

    #[ORM\OneToOne(targetEntity: Illustration::class)]
    private Illustration $thumbnail;

    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return Collection<int, Illustration>
     */
    public function getIllustrations(): Collection
    {
        return $this->illustrations;
    }

    public function addIllustration(Illustration $illustrations): self
    {
        $this->illustrations->add($illustrations);

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideoLinks(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        $this->videos->add($video);

        return $this;
    }

    public function getThumbnail(): Illustration
    {
        return $this->thumbnail;
    }

    public function setThumbnail(Illustration $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
