<?php

declare(strict_types=1);

namespace App\Infrastructure\Tricks\Entity;

use App\Infrastructure\Tricks\Repository\TricksRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
#[ORM\Table(name: "`tricks`")]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private string $name;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $imageUrl;

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setImageUrl(string $url): self
    {
        $this->name = $url;

        return $this;
    }
}
