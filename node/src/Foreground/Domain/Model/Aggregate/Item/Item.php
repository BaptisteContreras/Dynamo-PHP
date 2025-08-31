<?php

namespace App\Foreground\Domain\Model\Aggregate\Item;

use App\Shared\Domain\Model\Versioning\VectorClock;
use Symfony\Component\Uid\UuidV7;

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
    public static function create(
        string $key,
        VectorClock $version,
        int $ringKey,
        string $data,
        UuidV7 $ownerId,
        \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ): self {
        return new self(
            $key,
            new Metadata($version, $ringKey, $createdAt, $ownerId),
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

    public function getVersion(): VectorClock
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
