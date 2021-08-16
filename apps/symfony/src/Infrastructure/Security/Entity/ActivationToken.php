<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "`activation_tokens`")]
class ActivationToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 180)]
    private string $token;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tokens')]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public static function createForUser(User $user): self
    {
        return (new self())
            ->setUser($user)
            ->setToken("bla");
    }
}
