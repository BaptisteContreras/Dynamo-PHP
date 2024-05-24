<?php

namespace App\Background\Application\Command\Local\Join;

use App\Background\Domain\Out\History\CreatorInterface;
use App\Background\Domain\Out\History\FinderInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;
use Symfony\Component\Uid\UuidV7;

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
        $localTimeline->addLocalJoinEvent(UuidV7::fromString($event->getSelfNodeId()));

        $this->historyTimelineCreator->saveHistoryTimeline($localTimeline);

        // TODO sync seeds
    }
}
