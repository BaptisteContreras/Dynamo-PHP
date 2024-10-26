<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Model\Aggregate\Put\Item;

interface PutCoordinatorInterface
{
    public function isLocalNodeOwnerOf(Item $item): bool;

    public function forwardWrite(Item $item): void;
}
