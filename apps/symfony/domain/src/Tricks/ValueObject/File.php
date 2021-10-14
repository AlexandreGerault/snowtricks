<?php

namespace Domain\Tricks\ValueObject;

class File
{
    public function __construct(public string $filename, public string $content)
    {
    }
}
