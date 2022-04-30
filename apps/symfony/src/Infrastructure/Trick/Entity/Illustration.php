<?php

declare(strict_types=1);

namespace App\Infrastructure\Trick\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table('`trick_illustrations`')]
class Illustration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $path;

    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    #[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'illustrations')]
    private Trick $trick;

    public function setPath(string $path): Illustration
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setTrick(Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
