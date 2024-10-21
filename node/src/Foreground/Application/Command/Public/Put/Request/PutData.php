<?php

namespace App\Foreground\Application\Command\Public\Put\Request;

final readonly class PutData
{
    private PutMetadata $metadata;

    private string $rawData;

    public function __construct(PutMetadata $metadata, string $rawData)
    {
        $this->metadata = $metadata;
        $this->rawData = $rawData;
    }

    public function getMetadata(): PutMetadata
    {
        return $this->metadata;
    }

    public function getRawData(): string
    {
        return $this->rawData;
    }
}
