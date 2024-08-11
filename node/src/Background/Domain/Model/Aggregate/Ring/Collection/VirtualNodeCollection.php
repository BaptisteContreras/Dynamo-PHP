<?php

namespace App\Background\Domain\Model\Aggregate\Ring\Collection;

use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Shared\Domain\Model\Collection\MutableCollection;
use App\Shared\Domain\Model\Collection\MutableCollectionInterface;

/**
 * @implements MutableCollectionInterface<VirtualNode>
 */
class VirtualNodeCollection extends RoVirtualNodeCollection implements MutableCollectionInterface
{
    /**
     * @use MutableCollection<VirtualNode>
     */
    use MutableCollection;

    public function sortBySlot(): void
    {
        uasort($this->internal, function (VirtualNode $a, VirtualNode $b) {
            return $a->getSlot() > $b->getSlot() ? 1 : -1;
        });
    }
}
