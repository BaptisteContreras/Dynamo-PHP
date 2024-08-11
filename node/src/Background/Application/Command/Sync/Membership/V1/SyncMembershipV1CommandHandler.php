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
use App\Background\Domain\Service\PreferenceList\PreferenceListBuilder;

final readonly class SyncMembershipV1CommandHandler
{
    public function __construct(
        private HistoryFinderInterface $historyFinder,
        private HistoryUpdaterInterface $historyCreator,
        private RingFinderInterface $ringFinder,
        private RingUpdaterInterface $ringUpdater,
        private PreferenceListBuilder $preferenceListBuilder
    ) {
    }

    public function __invoke(SyncRequest $syncRequest, SyncMembershipPresenter $syncMembershipPresenter): void
    {
        $localHistory = $this->syncHistory($syncRequest);

        $ring = $this->syncRing($syncRequest, $localHistory);

        // update preference list
        $this->preferenceListBuilder->buildFromRing($ring);

        $syncMembershipPresenter->present(SyncMembershipV1Response::success());
    }

    private function syncHistory(SyncRequest $syncRequest): History
    {
        $localHistory = $this->historyFinder->getLocalHistoryTimeline();
        $remoteHistory = $this->createTimelineFromRequest($syncRequest);

        $this->historyCreator->saveHistoryTimeline($localHistory->merge($remoteHistory));

        return $localHistory;
    }

    private function syncRing(SyncRequest $syncRequest, History $historyTimeline): Ring
    {
        $remoteRing = $this->createRingFromRequest($syncRequest);
        $localRing = $this->ringFinder->getLocalRing();

        $localRing->merge($remoteRing, $historyTimeline);

        $this->ringUpdater->saveRing($localRing);

        return $localRing;
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
