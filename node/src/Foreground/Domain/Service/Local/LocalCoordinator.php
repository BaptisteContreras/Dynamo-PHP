<?php

namespace App\Foreground\Domain\Service\Local;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Put\Item;

class LocalCoordinator
{
    public function handleWriteLocally(Item $item): Node
    {
        // check if current node support the keys
        // if not, forward it to preference list, until a node handle it

        // If current node can handle it :
        // - check the version
        // - handle local write
        // - propagate write to W replicas

        // UUID | KEY | VERSION | ACTIVE | TIMESTAMP | DATA

        dd('todo');
    }
}
