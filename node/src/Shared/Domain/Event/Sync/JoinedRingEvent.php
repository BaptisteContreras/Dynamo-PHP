<?php

namespace App\Shared\Domain\Event\Sync;

final class JoinedRingEvent extends SyncDomainEvent
{
    /**
     * @param array<array{'host': string, 'networkPort': positive-int}> $initalSeeds
     */
    public function __construct(
        private readonly string $selfNodeId,
        private readonly array $initalSeeds
    ) {
    }

    public function getSelfNodeId(): string
    {
        return $this->selfNodeId;
    }

    /**
     * @return array<array{'host': string, 'networkPort': positive-int}>
     */
    public function getInitalSeeds(): array
    {
        return $this->initalSeeds;
    }
}
