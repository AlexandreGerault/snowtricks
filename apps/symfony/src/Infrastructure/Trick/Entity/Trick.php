<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Entity;

use App\Infrastructure\Trick\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[ORM\Table(name: '`tricks`')]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: "uuid")]
    private AbstractUid $uuid;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    private Category $category;

    /** @var Collection<int, Illustration> */
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Illustration::class, cascade: ["remove"])]
    private Collection $illustrations;

    /** @var Collection<int, Video> */
    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class, cascade: ["remove"])]
    private Collection $videos;

    #[ORM\OneToOne(targetEntity: Illustration::class, cascade: ["remove"])]
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

    public function addIllustration(Illustration $illustrations): self
    {
        $this->illustrations->add($illustrations);

        return $this;
    }

    public function getIllustrationPaths(): array
    {
        return $this
            ->illustrations
            ->map(fn (Illustration $illustration) => $illustration->getPath())
            ->toArray();
    }

    public function addVideo(Video $video): self
    {
        $this->videos->add($video);

        return $this;
    }

    public function removeVideo(string $videoLink): Video
    {
        $video = $this->videos->filter(fn (Video $video) => $videoLink === $video->getLink())->first();
        $this->videos->removeElement($video);

        return $video;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideoLinks(): Collection
    {
        return $this->videos;
    }

    public function setVideoLinks(array $links): void
    {
    }

    public function getUuid(): AbstractUid
    {
        return $this->uuid;
    }

    public function setUuid(AbstractUid $uuid): void
    {
        $this->uuid = $uuid;
    }
}
