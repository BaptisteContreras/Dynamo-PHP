<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Exception\CannotForwardWriteException;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Put\Item;

interface PutCoordinatorInterface
{
    public function isLocalNodeOwnerOf(Item $item): bool;

    /**
     * @return Node that handled the write
     *
     * @throws CannotForwardWriteException if this method failed to forward the write
     */
    public function forwardWrite(Item $item): Node;
}
