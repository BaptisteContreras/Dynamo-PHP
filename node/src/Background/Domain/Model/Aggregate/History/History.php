<?php

namespace App\Background\Domain\Model\Aggregate\History;

use App\Background\Domain\Model\Aggregate\History\Collection\HistoryEventCollection;
use App\Background\Domain\Model\Aggregate\History\Collection\RoHistoryEventCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Shared\Domain\Const\HistoryEventType;
use App\Shared\Domain\Const\MembershipState;
use Symfony\Component\Uid\UuidV7;

class History
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

    public function addEvent(Event $event): self
    {
        if (!$this->events->exists($event)) {
            $this->newEventsCollection->add($event);
            $this->events->add($event);
        }

        return $this;
    }

    public function addRemoteEvent(UuidV7|string $id, UuidV7|string $node, HistoryEventType $type, \DateTimeImmutable $eventTime, UuidV7|string $sourceNode, string $data): self
    {
        $id = $id instanceof UuidV7 ? $id : UuidV7::fromString($id);
        $node = $node instanceof UuidV7 ? $node : UuidV7::fromString($node);
        $sourceNode = $sourceNode instanceof UuidV7 ? $sourceNode : UuidV7::fromString($sourceNode);

        return $this->addEvent(new Event(
            $id,
            $node,
            $type,
            $eventTime,
            $data,
            $sourceNode,
            new \DateTimeImmutable()
        ));
    }

    public function addLocalJoinEvent(UuidV7|string $node): self
    {
        $node = $node instanceof UuidV7 ? $node : UuidV7::fromString($node);

        $this->addEvent(Event::localEvent($node, HistoryEventType::CHANGE_MEMBERSHIP, (string) MembershipState::JOINED->value));

        return $this;
    }

    public function addLocalLeaveEvent(UuidV7|string $node): self
    {
        $node = $node instanceof UuidV7 ? $node : UuidV7::fromString($node);

        $this->addEvent(Event::localEvent($node, HistoryEventType::CHANGE_MEMBERSHIP, (string) MembershipState::LEFT->value));

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

    public function applyEventsForNode(Node $node): void
    {
        $nodeEvents = $this->events->filter(fn (Event $event) => $node->hasSameId($event->getNode()));

        /** @var array<string, Event> $mostRecentEventByType */
        $mostRecentEventByType = [];

        foreach ($nodeEvents as $event) {
            $currentMostRecentEvent = $mostRecentEventByType[$event->getType()->name] ?? null;

            if (!$currentMostRecentEvent || $event->isNewerThan($currentMostRecentEvent)) {
                $mostRecentEventByType[$event->getType()->name] = $event;
            }
        }

        foreach ($mostRecentEventByType as $eventToApply) {
            $node->applyEvent($eventToApply);
        }
    }
}
