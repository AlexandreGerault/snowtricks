<?php

declare(strict_types=1);

namespace Domain\Trick\UseCase\RegisterNewTrick;

use Domain\Trick\ValueObject\File;

class RegisterNewTrickRequest
{
    /** @var File[] */
    public array $illustrations;

    /** @var string[] */
    public array $videosUrls;

    public function __construct(
        public File $thumbnail,
        public string $name = '',
        public string $description = '',
        public string $category = '',
        array $videosUrls = [],
        array $illustrations = [],
    ) {
        $this->illustrations = $illustrations;
        $this->videosUrls = $videosUrls;
    }
}
