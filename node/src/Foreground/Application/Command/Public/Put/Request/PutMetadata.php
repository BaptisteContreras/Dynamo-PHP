<?php

namespace App\Foreground\Application\Command\Public\Put\Request;

final readonly class PutMetadata
{
    private string $version; // TODO transform to vector clock

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
