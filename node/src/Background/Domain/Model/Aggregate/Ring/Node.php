<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use App\Background\Domain\Model\Aggregate\Ring\Collection\RoVirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Shared\Domain\Const\NodeState;
use Symfony\Component\Uid\UuidV7;

final readonly class Node
{
    public function __construct(
        private UuidV7 $id,
        private string $host,
        private int $networkPort,
        private NodeState $state,
        private \DateTimeImmutable $joinedAt,
        private int $weight,
        private bool $seed,
        private \DateTimeImmutable $updatedAt,
        private string $label,
        private VirtualNodeCollection $virtualNodeCollection = new VirtualNodeCollection()
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getState(): NodeState
    {
        return $this->state;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getVirtualNodeCollection(): RoVirtualNodeCollection
    {
        return $this->virtualNodeCollection;
    }
}
