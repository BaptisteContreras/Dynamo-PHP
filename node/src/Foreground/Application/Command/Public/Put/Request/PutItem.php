<?php

namespace App\Foreground\Application\Command\Public\Put\Request;

final readonly class PutItem
{
    private string $key;

    private PutMetadata $metadata;

    private string $data;

    public function __construct(string $key, PutMetadata $metadata, string $data)
    {
        $this->key = $key;
        $this->metadata = $metadata;
        $this->data = $data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getMetadata(): PutMetadata
    {
        return $this->metadata;
    }

    public function getData(): string
    {
        return $this->data;
    }
}
