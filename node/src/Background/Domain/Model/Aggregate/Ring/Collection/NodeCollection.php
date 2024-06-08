<?php

namespace App\Background\Domain\Model\Aggregate\Ring\Collection;

use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Shared\Domain\Model\Collection\MutableCollection;
use App\Shared\Domain\Model\Collection\MutableCollectionInterface;

/**
 * @implements MutableCollectionInterface<Node>
 */
class NodeCollection extends RoNodeCollection implements MutableCollectionInterface
{
    /**
     * @use MutableCollection<Node>
     */
    use MutableCollection;
}
