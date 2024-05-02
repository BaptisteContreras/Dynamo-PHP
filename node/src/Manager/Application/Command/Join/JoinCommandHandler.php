<?php

namespace App\Manager\Application\Command\Join;

use App\Manager\Application\Command\Join\Presenter\JoinPresenter;
use App\Manager\Application\Command\Join\Response\JoinResponse;
use App\Manager\Domain\Exception\AlreadyJoinedException;
use App\Manager\Domain\Model\Node;
use App\Manager\Domain\Out\Node\CreatorInterface;
use App\Manager\Domain\Out\Node\FinderInterface;
use App\Shared\Domain\Out\StorageTransactionManagerInterface;

readonly class JoinCommandHandler
{
    public function __construct(
        private StorageTransactionManagerInterface $storageTransactionManager,
        private CreatorInterface $nodeCreator,
        private FinderInterface $nodeFinder
    ) {
    }

    public function __invoke(JoinRequest $joinRequest, JoinPresenter $joinPresenter): void
    {
        $this->canJoin();

        $this->nodeCreator->createSelfNode(
            $joinRequest->getHost(),
            $joinRequest->getNetworkPort(),
            $joinRequest->getWeight(),
            $joinRequest->isSeed(),
            new \DateTimeImmutable()
        );

        $this->storageTransactionManager->flush();

        /** @var Node $selfNode */
        $selfNode = $this->nodeFinder->findSelfEntry();

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
}
