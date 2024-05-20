<?php

namespace App\Manager\Domain\Model\Aggregate\Node\Collection;

use App\Manager\Domain\Model\Aggregate\Node\VirtualNode;
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
}
