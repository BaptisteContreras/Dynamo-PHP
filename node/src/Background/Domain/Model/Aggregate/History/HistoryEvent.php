<?php

namespace App\Background\Domain\Model\Aggregate\History;

use App\Shared\Domain\Const\HistoryEventType;
use Symfony\Component\Uid\UuidV7;

final readonly class HistoryEvent
{
    public function __construct(
        private UuidV7 $id,
        private UuidV7 $node,
        private HistoryEventType $type,
        private \DateTimeImmutable $eventTime,
        private ?UuidV7 $sourceNode,
        private ?\DateTimeImmutable $receivedAt
    ) {
    }

    public static function localEvent(UuidV7 $node, HistoryEventType $type): self
    {
        return new self(
            new UuidV7(),
            $node,
            $type,
            new \DateTimeImmutable(),
            null,
            null
        );
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getNode(): UuidV7
    {
        return $this->node;
    }

    public function getType(): HistoryEventType
    {
        return $this->type;
    }

    public function getEventTime(): \DateTimeImmutable
    {
        return $this->eventTime;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function getSourceNode(): ?UuidV7
    {
        return $this->sourceNode;
    }
}
