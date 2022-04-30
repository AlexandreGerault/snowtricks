<?php

declare(strict_types=1);

namespace App\UserInterface\Trick\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CommentTrickFormModel
{
    #[Assert\NotBlank]
    public string $content;
}
