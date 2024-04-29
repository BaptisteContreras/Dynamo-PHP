<?php

namespace App\Manager\Domain\Model;

use App\Shared\Domain\Const\NodeState;
use Symfony\Component\Uid\UuidV7;

final class Node
{
    public function __construct(
        private readonly UuidV7 $id,
        private readonly string $networkAddress,
        private readonly int $networkPort,
        private NodeState $state,
        private readonly \DateTimeImmutable $joinedAt,
        private int $weight,
        private readonly bool $selfEntry
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
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

    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    public function isSelfEntry(): bool
    {
        return $this->selfEntry;
    }
}
