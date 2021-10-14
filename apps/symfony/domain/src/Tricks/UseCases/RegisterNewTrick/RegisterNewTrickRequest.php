<?php

declare(strict_types=1);

namespace Domain\Tricks\UseCases\RegisterNewTrick;

use Domain\Tricks\ValueObject\File;

class RegisterNewTrickRequest
{
    /** @var File[] */
    public array $illustrations;

    /** @var string[] */
    public array $videosUrls;

    public function __construct(
        public File $thumbnail,
        public string $name = "",
        public string $description = "",
        public string $category = "",
        array $videosUrls = [],
        array $illustrations = [],
    ) {
        $this->illustrations = $illustrations;
        $this->videosUrls = $videosUrls;
    }
}
