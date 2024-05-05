<?php

namespace App\Manager\Application\Command\Join;

use App\Manager\Application\Command\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Join\Request\JoinRequest;
use App\Manager\Application\Command\Join\Request\SeedRequest;
use App\Manager\Application\Command\Join\Response\JoinResponse;
use App\Manager\Domain\Exception\AlreadyJoinedException;
use App\Manager\Domain\Out\Node\CreatorInterface;
use App\Manager\Domain\Out\Node\FinderInterface;
use App\Manager\Domain\Service\VirtualNode\Attribution\VirtualNodeAttributor;
use App\Shared\Domain\Event\EventBusInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;

final readonly class JoinCommandHandler
{
    public function __construct(
        private CreatorInterface $nodeCreator,
        private FinderInterface $nodeFinder,
        private EventBusInterface $eventBus,
        private VirtualNodeAttributor $virtualNodeAttributor
    ) {
    }

    public function __invoke(JoinRequest $joinRequest, JoinPresenter $joinPresenter): void
    {
        $this->canJoin();

        $selfNodeRequest = $joinRequest->getSelfNode();
        $ringConfigRequest = $joinRequest->getConfig();

        $selfNode = $this->nodeCreator->createSelfNode(
            $selfNodeRequest->getHost(),
            $selfNodeRequest->getNetworkPort(),
            $selfNodeRequest->getWeight(),
            $selfNodeRequest->isSeed(),
            $selfNodeRequest->getLabel(),
            new \DateTimeImmutable()
        );

        $this->virtualNodeAttributor->attributeVirtualNodes($selfNode, $ringConfigRequest->getVirtualNodeAttributionStrategy());

        $this->nodeCreator->saveNode($selfNode);

        $this->eventBus->publish(new JoinedRingEvent(
            $selfNode->getId()->toRfc4122(),
            $this->convertSeedsForEvent($joinRequest->getInitialSeeds())
        ));

        $joinPresenter->present(JoinResponse::success($selfNode));
    }

    /**
     * @throws AlreadyJoinedException
     */
    private function canJoin(): void
    {
        if ($this->nodeFinder->findSelfEntry()) {
            throw new AlreadyJoinedException();
        }
    }

    /**
     * @param array<SeedRequest> $seeds
     *
     * @return array<array{'host': string, 'networkPort': positive-int}>
     */
    private function convertSeedsForEvent(array $seeds): array
    {
        return array_map(fn (SeedRequest $seedRequest) => [
            'host' => $seedRequest->getHost(),
            'networkPort' => $seedRequest->getNetworkPort(),
        ], $seeds);
    }
}
