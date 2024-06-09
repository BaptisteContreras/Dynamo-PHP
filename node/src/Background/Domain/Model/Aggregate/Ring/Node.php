<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use App\Background\Domain\Model\Aggregate\History\Event;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoVirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Shared\Domain\Const\HistoryEventType;
use App\Shared\Domain\Const\NodeState;
use Symfony\Component\Uid\UuidV7;

final class Node
{
    public function __construct(
        private readonly UuidV7 $id,
        private string $host,
        private int $networkPort,
        private NodeState $state,
        private readonly \DateTimeImmutable $joinedAt,
        private int $weight,
        private bool $seed,
        private \DateTimeImmutable $updatedAt,
        private readonly string $label,
        private VirtualNodeCollection $virtualNodeCollection = new VirtualNodeCollection(),
        private readonly bool $local = false,
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getStringId(): string
    {
        return $this->id->toRfc4122();
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

    public function isLocal(): bool
    {
        return $this->local;
    }

    public function isFresherThan(self $otherNode): bool
    {
        return $this->updatedAt >= $otherNode->getUpdatedAt();
    }

    public function isLeavingRing(): bool
    {
        return NodeState::LEAVING === $this->state;
    }

    public function applyEvent(Event $event): self
    {
        match ($event->getType()) {
            HistoryEventType::JOIN => $this->state = NodeState::JOINING,
            HistoryEventType::LEAVE => $this->state = NodeState::LEAVING,
        };

        return $this;
    }
}
