<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoVirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;

final class Ring
{
    private readonly VirtualNodeCollection $virtualNodeCollection;
    private VirtualNodeCollection $internalRing;
    private VirtualNodeCollection $unusedVirtualNodeCollection;

    public function __construct(
        private readonly NodeCollection $nodeCollection = new NodeCollection(),
    ) {
        $this->virtualNodeCollection = VirtualNodeCollection::createEmpty();
        $this->internalRing = VirtualNodeCollection::createEmpty();
        $this->unusedVirtualNodeCollection = VirtualNodeCollection::createEmpty();

        foreach ($this->nodeCollection as $node) {
            $this->virtualNodeCollection->merge($node->getVirtualNodeCollection());
        }

        $this->updateInternalRing();
    }

    public function getNodes(): RoNodeCollection
    {
        return $this->nodeCollection;
    }

    public function addNode(Node $node): self
    {
        $this->nodeCollection->add($node);
        $this->virtualNodeCollection->merge($node->getVirtualNodeCollection());

        $this->updateInternalRing();

        return $this;
    }

    public function getVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->internalRing;
    }

    private function updateInternalRing(): void
    {
        $this->internalRing = VirtualNodeCollection::createEmpty();
        $this->unusedVirtualNodeCollection = VirtualNodeCollection::createEmpty();

        /** @var array<int, VirtualNode> $slotMap */
        $slotMap = [];

        foreach ($this->virtualNodeCollection as $virtualNode) {
            if ($alreadyAssignedVirtualNode = ($slotMap[$virtualNode->getSlot()] ?? null)) {
                if ($alreadyAssignedVirtualNode->isNewerThan($virtualNode)) {
                    $this->unusedVirtualNodeCollection->add($virtualNode);

                    continue;
                }

                $this->internalRing->remove($alreadyAssignedVirtualNode);
                $this->unusedVirtualNodeCollection->add($alreadyAssignedVirtualNode);
            }

            $this->internalRing->add($virtualNode);
            $slotMap[$virtualNode->getSlot()] = $virtualNode;
        }
    }
}
