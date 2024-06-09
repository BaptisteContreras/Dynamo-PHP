<?php

namespace App\Background\Application\Command\Sync\Membership\V1;

use App\Background\Application\Command\Sync\Membership\V1\Presenter\SyncMembershipPresenter;
use App\Background\Application\Command\Sync\Membership\V1\Request\NodeRequest;
use App\Background\Application\Command\Sync\Membership\V1\Request\SyncRequest;
use App\Background\Application\Command\Sync\Membership\V1\Response\SyncMembershipV1Response;
use App\Background\Domain\Model\Aggregate\History\History;
use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Out\History\FinderInterface as HistoryFinderInterface;
use App\Background\Domain\Out\History\UpdaterInterface as HistoryUpdaterInterface;
use App\Background\Domain\Out\Ring\FinderInterface as RingFinderInterface;
use App\Background\Domain\Out\Ring\UpdaterInterface as RingUpdaterInterface;

final readonly class SyncMembershipV1CommandHandler
{
    public function __construct(
        private HistoryFinderInterface $historyFinder,
        private HistoryUpdaterInterface $historyCreator,
        private RingFinderInterface $ringFinder,
        private RingUpdaterInterface $ringUpdater
    ) {
    }

    public function __invoke(SyncRequest $syncRequest, SyncMembershipPresenter $syncMembershipPresenter): void
    {
        $localHistory = $this->syncHistory($syncRequest);

        $this->syncNodes($syncRequest, $localHistory);

        // update nodes
        // update virtual nodes

        $syncMembershipPresenter->present(SyncMembershipV1Response::success());
    }

    private function syncHistory(SyncRequest $syncRequest): History
    {
        $localHistory = $this->historyFinder->getLocalHistoryTimeline();
        $remoteHistory = $this->createTimelineFromRequest($syncRequest);

        $this->historyCreator->saveHistoryTimeline($localHistory->merge($remoteHistory));

        return $localHistory;
    }

    private function syncNodes(SyncRequest $syncRequest, History $historyTimeline): void
    {
        $remoteRing = $this->createRingFromRequest($syncRequest);
        dump($remoteRing);
        dump($this->ringFinder->getLocalRing());

        dd($this->ringFinder->getLocalRing()->merge($remoteRing, $historyTimeline));
    }

    private function createTimelineFromRequest(SyncRequest $syncRequest): History
    {
        $timeline = History::createEmpty();

        foreach ($syncRequest->getHistoryEvents() as $event) {
            $timeline->addRemoteEvent(
                $event->getId(),
                $event->getNode(),
                $event->getType(),
                $event->getEventTime(),
                $syncRequest->getSourceNode(),
                $event->getData()
            );
        }

        return $timeline;
    }

    private function createRingFromRequest(SyncRequest $syncRequest): Ring
    {
        return new Ring(new NodeCollection(
            array_map(fn (NodeRequest $nodeRequest) => $nodeRequest->asDto(), $syncRequest->getNodes())
        ));
    }
}
