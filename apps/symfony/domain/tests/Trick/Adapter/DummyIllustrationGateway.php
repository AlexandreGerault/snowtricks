<?php

namespace Domain\Tests\Trick\Adapter;

use Domain\Trick\Gateway\IllustrationGateway;

class DummyIllustrationGateway implements IllustrationGateway
{
    public function store(string $path, string $filename, string $content): void
    {
    }
}
