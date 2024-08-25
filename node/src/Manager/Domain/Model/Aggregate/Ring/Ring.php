<?php

namespace App\Manager\Domain\Model\Aggregate\Ring;

use App\Manager\Domain\Model\Aggregate\Node\Collection\RoVirtualNodeCollection;
use App\Manager\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Manager\Domain\Model\Aggregate\Node\VirtualNode;
use App\Shared\Domain\Const\RingInformations;
use Manager\Domain\Model\Aggregate\Ring\Slot;

final class Ring
{
    private VirtualNodeCollection $virtualNodes;

    /**
     * @param array<Node> $nodes
     */
    public function __construct(array $nodes)
    {
        $this->virtualNodes = VirtualNodeCollection::createEmpty();

        foreach ($nodes as $node) {
            $this->virtualNodes->merge($node->getVirtualNodes());
        }

        $this->virtualNodes = $this->virtualNodes->getActives();
        $this->virtualNodes->sortBySlot();
    }

    /**
     * @return array<Slot>
     */
    public function getSlots(): array
    {
        if ($this->virtualNodes->isEmpty()) {
            return [];
        }

        $slots = [];

        /** @var VirtualNode $firstVirtualNode */
        $firstVirtualNode = $this->virtualNodes->first();
        $firstSlot = $firstVirtualNode->getSlot();

        $startSlot = $firstSlot;
        $currentRangeVirtualNode = $firstVirtualNode;

        foreach ($this->virtualNodes as $virtualNode) {
            $endSlot = $virtualNode->getSlot();
            if ($startSlot !== $endSlot) {
                for ($i = 0; $i < $this->computeRangeDistance($startSlot, $endSlot); ++$i) {
                    $currentSlot = ($startSlot + $i) % RingInformations::RING_SIZE;
                    $slots[$currentSlot] = new Slot($currentSlot, $currentRangeVirtualNode);
                }

                $startSlot = $endSlot;
                $currentRangeVirtualNode = $virtualNode;
            }
        }

        $endSlot = ($firstSlot + (RingInformations::RING_SIZE - 1)) % RingInformations::RING_SIZE;

        for ($i = 0; $i < $this->computeRangeDistance($startSlot, $endSlot); ++$i) {
            $currentSlot = ($startSlot + $i) % RingInformations::RING_SIZE;
            $slots[$currentSlot] = new Slot($currentSlot, $currentRangeVirtualNode);
        }

        uasort($slots, function (Slot $a, Slot $b) {
            return $a->getSlot() > $b->getSlot() ? 1 : -1;
        });

        return $slots;
    }

    public function getVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->virtualNodes;
    }

    private function computeRangeDistance(int $startSlot, int $endSlot): int
    {
        return ($endSlot + (RingInformations::RING_SIZE - $startSlot)) % RingInformations::RING_SIZE;
    }
}
