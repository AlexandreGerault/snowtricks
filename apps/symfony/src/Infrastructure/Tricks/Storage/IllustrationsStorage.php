<?php

namespace App\Infrastructure\Tricks\Storage;

use Domain\Tricks\Gateway\IllustrationsGateway;
use Symfony\Component\Filesystem\Filesystem;

class IllustrationsStorage implements IllustrationsGateway
{
    public function __construct(private Filesystem $filesystem)
    {
    }

    public function store(string $path, string $filename, string $content): void
    {
        $this->filesystem->dumpFile("{$path}/{$filename}", $content);
    }
}
