<?php

namespace App\Background\Application\Command\Local\Join;

use App\Background\Domain\Out\History\CreatorInterface;
use App\Background\Domain\Out\History\FinderInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;

final readonly class LocalNodeJoinCommandHandler
{
    public function __construct(
        private FinderInterface $historyTimelineFinder,
        private CreatorInterface $historyTimelineCreator,
    ) {
    }

    public function __invoke(JoinedRingEvent $event): void
    {
        $localTimeline = $this->historyTimelineFinder->getLocalHistoryTimeline();
        $localTimeline->addLocalJoinEvent($event->getSelfNodeId());

        $this->historyTimelineCreator->saveHistoryTimeline($localTimeline);

        // TODO sync seeds
    }
}
