<?php

namespace App\Background\Domain\Model\Aggregate\History;

use App\Background\Domain\Model\Aggregate\History\Collection\HistoryEventCollection;
use App\Background\Domain\Model\Aggregate\History\Collection\RoHistoryEventCollection;
use App\Shared\Domain\Const\HistoryEventType;
use Symfony\Component\Uid\UuidV7;

final class HistoryTimeline
{
    private HistoryEventCollection $newEventsCollection;

    public function __construct(
        private readonly HistoryEventCollection $events = new HistoryEventCollection()
    ) {
        $this->newEventsCollection = HistoryEventCollection::createEmpty();
    }

    public static function createEmpty(): self
    {
        return new self();
    }

    public function addEvent(HistoryEvent $event): self
    {
        if (!$this->events->exists($event)) {
            $this->newEventsCollection->add($event);
            $this->events->add($event);
        }

        return $this;
    }

    public function addRemoteEvent(UuidV7|string $id, UuidV7|string $node, HistoryEventType $type, \DateTimeImmutable $eventTime, UuidV7|string $sourceNode): self
    {
        $id = $id instanceof UuidV7 ? $id : UuidV7::fromString($id);
        $node = $node instanceof UuidV7 ? $node : UuidV7::fromString($node);
        $sourceNode = $sourceNode instanceof UuidV7 ? $sourceNode : UuidV7::fromString($sourceNode);

        return $this->addEvent(new HistoryEvent(
            $id,
            $node,
            $type,
            $eventTime,
            $sourceNode,
            new \DateTimeImmutable()
        ));
    }

    public function addLocalJoinEvent(UuidV7|string $node): self
    {
        $node = $node instanceof UuidV7 ? $node : UuidV7::fromString($node);

        $this->addEvent(HistoryEvent::localEvent($node, HistoryEventType::JOIN));

        return $this;
    }

    public function addLocalLeaveEvent(UuidV7|string $node): self
    {
        $node = $node instanceof UuidV7 ? $node : UuidV7::fromString($node);

        $this->addEvent(HistoryEvent::localEvent($node, HistoryEventType::LEAVE));

        return $this;
    }

    public function merge(self $otherTimeline): self
    {
        foreach ($otherTimeline->getEvents() as $otherEvent) {
            $this->addEvent(clone $otherEvent);
        }

        return $this;
    }

    public function getEvents(): RoHistoryEventCollection
    {
        return $this->events;
    }

    public function getNewEvents(): RoHistoryEventCollection
    {
        $currentCollection = $this->newEventsCollection;
        $this->newEventsCollection = HistoryEventCollection::createEmpty();

        return $currentCollection;
    }
}
