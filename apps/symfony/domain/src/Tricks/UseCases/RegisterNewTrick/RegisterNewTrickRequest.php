<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\RegisterNewTrick;

class RegisterNewTrickRequest
{
    public function __construct(
        public string $name,
        public string $description,
        public string $category,
        public array $illustrations,
        public array $videos
    ) {
    }
}
