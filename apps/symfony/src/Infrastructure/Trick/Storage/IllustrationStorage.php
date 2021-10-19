<?php

namespace App\Infrastructure\Trick\Storage;

use Domain\Trick\Gateway\IllustrationGateway;
use Symfony\Component\Filesystem\Filesystem;

class IllustrationStorage implements IllustrationGateway
{
    public function __construct(private Filesystem $filesystem)
    {
    }

    public function store(string $path, string $filename, string $content): void
    {
        $this->filesystem->dumpFile("{$path}/{$filename}", $content);
    }
}
