<?php

namespace App\Background\Application\Command\Local\Join;

use App\Background\Domain\Out\History\FinderInterface;
use App\Background\Domain\Out\History\UpdaterInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;

final readonly class LocalNodeJoinCommandHandler
{
    public function __construct(
        private FinderInterface $historyTimelineFinder,
        private UpdaterInterface $historyTimelineCreator,
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
