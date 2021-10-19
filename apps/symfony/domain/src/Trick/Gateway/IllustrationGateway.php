<?php

namespace Domain\Trick\Gateway;

interface IllustrationGateway
{
    public function store(string $path, string $filename, string $content): void;
}
