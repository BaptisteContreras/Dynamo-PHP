<?php

namespace App\Background\Application\Command\Sync\Membership\V1;

use App\Background\Application\Command\Sync\Membership\V1\Presenter\SyncMembershipPresenter;
use App\Background\Application\Command\Sync\Membership\V1\Request\SyncRequest;
use App\Background\Application\Command\Sync\Membership\V1\Response\SyncMembershipV1Response;
use App\Background\Domain\Model\Aggregate\History\HistoryTimeline;
use App\Background\Domain\Out\History\CreatorInterface;
use App\Background\Domain\Out\History\FinderInterface;

final readonly class SyncMembershipV1CommandHandler
{
    public function __construct(
        private FinderInterface $historyFinder,
        private CreatorInterface $historyCreator
    ) {
    }

    public function __invoke(SyncRequest $syncRequest, SyncMembershipPresenter $syncMembershipPresenter): void
    {
        $localHistory = $this->historyFinder->getLocalHistoryTimeline();

        $remoteHistory = $this->createTimelineFromRequest($syncRequest);

        $mergedLocalHistory = $localHistory->merge($remoteHistory);

        $this->historyCreator->saveHistoryTimeline($mergedLocalHistory);

        // update nodes
        // update virtual nodes

        $syncMembershipPresenter->present(SyncMembershipV1Response::success());
    }

    private function createTimelineFromRequest(SyncRequest $syncRequest): HistoryTimeline
    {
        $timeline = HistoryTimeline::createEmpty();

        foreach ($syncRequest->getHistoryEvents() as $event) {
            $timeline->addRemoteEvent(
                $event->getId(),
                $event->getNode(),
                $event->getType(),
                $event->getEventTime(),
                $syncRequest->getSourceNode()
            );
        }

        return $timeline;
    }
}
