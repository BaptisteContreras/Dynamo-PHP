<?php

namespace App\Foreground\Domain\Model\Aggregate\Node\Collection;

use App\Foreground\Domain\Model\Aggregate\Node\VirtualNode;
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
