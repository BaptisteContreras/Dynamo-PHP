<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;

readonly class Coordinator implements PutCoordinatorInterface
{
    public function __construct(
        private PreferenceListFinder $preferenceListFinder,
        private NodeFinder $nodeFinder,
    ) {
    }

    public function isLocalNodeOwnerOf(Item $item): bool
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();
        $localNode = $this->nodeFinder->getLocalEntry();

        $entryForSlot = $preferenceList->getEntry($item->getRingKey());

        return $entryForSlot->getOwnerId()->equals($localNode->getId());
    }

    public function forwardWrite(Item $item): void
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();
        $entryForSlot = $preferenceList->getEntry($item->getRingKey());

        $slotCoordinators = $entryForSlot->getCoordinatorsPriorityList();

        foreach ($slotCoordinators as $slotCoordinator) {
        }
    }
}
