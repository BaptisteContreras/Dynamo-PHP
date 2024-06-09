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

    public function getVirtualNodes(): RoVirtualNodeCollection
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
        $data = $event->getData();

        match ($event->getType()) {
            HistoryEventType::CHANGE_MEMBERSHIP => $this->state = NodeState::from((int) $data),
            HistoryEventType::CHANGE_HOST => $this->host = $data,
            HistoryEventType::CHANGE_NETWORK_PORT => $this->networkPort = (int) $data,
            HistoryEventType::CHANGE_WEIGHT => $this->weight = (int) $data,
            HistoryEventType::CHANGE_SEED => $this->seed = 'true' === $data,
        };

        return $this;
    }

    public function hasSameId(UuidV7|self|string $other): bool
    {
        if ($other instanceof self) {
            $other = $other->getId();
        } elseif (is_string($other)) {
            $other = UuidV7::fromString($other);
        }

        return $this->id->equals($other);
    }
}
