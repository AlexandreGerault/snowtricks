<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: '`trick_videos`')]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $link;

    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'videos')]
    private Trick $trick;

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function setTrick(Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
