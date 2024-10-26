<?php

namespace App\Foreground\Domain\Model\Aggregate\Put;

final readonly class Item
{
    public function __construct(
        private string $key,
        private Metadata $metadata,
        private string $data,
    ) {
    }

    /**
     * @param int<0, 360> $ringKey
     */
    public static function create(string $key, string $version, int $ringKey, string $data): self
    {
        return new self(
            $key,
            new Metadata($version, $ringKey),
            $data
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getMetadata(): Metadata
    {
        return $this->metadata;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getVersion(): string
    {
        return $this->metadata->getVersion();
    }

    /**
     * @return int<0, 360>
     */
    public function getRingKey(): int
    {
        return $this->metadata->getRingKey();
    }
}
