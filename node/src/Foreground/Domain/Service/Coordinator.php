<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;
use App\Foreground\Domain\Service\Forward\Forwarder;
use App\Foreground\Domain\Service\Local\LocalCoordinator;

readonly class Coordinator implements PutCoordinatorInterface
{
    public function __construct(
        private PreferenceListFinder $preferenceListFinder,
        private NodeFinder $nodeFinder,
        private LocalCoordinator $localCoordinator,
        private Forwarder $forwarder
    ) {
    }

    public function handleWrite(Item $item): Node
    {
        if ($this->isLocalNodeOwnerOf($item)) {
            return $this->localCoordinator->handleWriteLocally($item);
        }

        return $this->forwarder->forwardWrite($item);
    }

    private function isLocalNodeOwnerOf(Item $item): bool
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();
        $localNode = $this->nodeFinder->getLocalEntry();

        $entryForSlot = $preferenceList->getEntry($item->getRingKey());

        return $entryForSlot->getOwnerId()->equals($localNode->getId());
    }
}
