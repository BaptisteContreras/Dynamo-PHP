<?php

namespace Manager\Domain\Model\Aggregate\Ring;

use App\Manager\Domain\Model\Aggregate\Node\VirtualNode;

final readonly class Slot
{
    public function __construct(
        private int $slot,
        private VirtualNode $virtualNode
    ) {
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getVirtualNode(): VirtualNode
    {
        return $this->virtualNode;
    }
}
