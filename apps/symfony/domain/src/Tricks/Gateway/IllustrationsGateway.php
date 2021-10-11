<?php

namespace Domain\Tricks\Gateway;

interface IllustrationsGateway
{
    public function store(string $path, string $filename, string $content): void;
}
