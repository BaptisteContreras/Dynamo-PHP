<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;

readonly class Coordinator implements PutCoordinatorInterface
{
    public function __construct(
        private PreferenceListFinder $preferenceListFinder,
        private NodeFinder $nodeFinder,
    ) {
    }

    public function isLocalNodeOwnerOf(int $ringKey): bool
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();
        $localNode = $this->nodeFinder->getLocalEntry();

        $entryForSlot = $preferenceList->getEntry($ringKey);

        return $entryForSlot->getOwnerId()->equals($localNode->getId());
    }

    public function forwardWrite()
    {
    }
}
