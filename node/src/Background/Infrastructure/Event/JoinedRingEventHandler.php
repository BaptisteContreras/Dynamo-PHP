<?php

namespace App\Background\Infrastructure\Event;

use App\Background\Domain\Out\History\CreatorInterface;
use App\Background\Domain\Out\History\FinderInterface;
use App\Shared\Domain\Event\EventHandlerInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;
use Symfony\Component\Uid\UuidV7;

class JoinedRingEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly FinderInterface $historyTimelineFinder,
        private readonly CreatorInterface $historyTimelineCreator,
    ) {
    }

    public function __invoke(JoinedRingEvent $event): void
    {
        $localTimeline = $this->historyTimelineFinder->getLocalHistoryTimeline();
        $localTimeline->addLocalJoinEvent(UuidV7::fromString($event->getSelfNodeId()));

        $this->historyTimelineCreator->saveHistoryTimeline($localTimeline);

        // TODO sync seeds
    }
}
