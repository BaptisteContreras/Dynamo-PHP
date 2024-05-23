<?php

namespace App\Background\Domain\Model\Aggregate\History;

use App\Background\Domain\Model\Aggregate\History\Collection\HistoryEventCollection;
use App\Background\Domain\Model\Aggregate\History\Collection\RoHistoryEventCollection;
use App\Shared\Domain\Const\HistoryEventType;
use Symfony\Component\Uid\UuidV7;

final readonly class HistoryTimeline
{
    public function __construct(
        private HistoryEventCollection $events
    ) {
    }

    public function addEvent(HistoryEvent $event): self
    {
        if (!$this->events->exists($event)) {
            $this->events->add($event);
        }

        return $this;
    }

    public function addLocalJoinEvent(UuidV7 $node): self
    {
        $this->events->add(HistoryEvent::localEvent($node, HistoryEventType::JOIN));

        return $this;
    }

    public function addLocalLeaveEvent(UuidV7 $node): self
    {
        $this->events->add(HistoryEvent::localEvent($node, HistoryEventType::LEAVE));

        return $this;
    }

    public function merge(self $otherTimeline): self
    {
        $mergerCollection = new HistoryEventCollection();

        return new self($mergerCollection);
    }

    public function getEvents(): RoHistoryEventCollection
    {
        return $this->events;
    }
}
