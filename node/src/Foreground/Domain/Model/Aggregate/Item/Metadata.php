<?php

namespace App\Foreground\Domain\Model\Aggregate\Item;

use App\Shared\Domain\Model\Versioning\VectorClock;
use Symfony\Component\Uid\UuidV7;

final class Metadata
{
    /**
     * @param int<0, 360> $ringKey
     */
    public function __construct(
        private VectorClock $version,
        private readonly int $ringKey,
        private readonly \DateTimeImmutable $createdAt,
        private readonly UuidV7 $ownerId,
    ) {
    }

    public function getVersion(): VectorClock
    {
        return clone $this->version; // Ensure no update outside
    }

    /**
     * @return int<0, 360>
     */
    public function getRingKey(): int
    {
        return $this->ringKey;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getOwnerId(): UuidV7
    {
        return $this->ownerId;
    }

    public function tickVectorClock(): self
    {
        $this->version->tick($this->ownerId->toRfc4122());

        return $this;
    }
}
