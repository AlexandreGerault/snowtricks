<?php

declare(strict_types=1);

namespace App\UserInterface\Security\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterFormModel
{
    #[Assert\NotBlank]
    public string $username = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    public string $password = '';
}
