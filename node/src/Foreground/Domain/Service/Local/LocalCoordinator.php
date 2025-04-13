<?php

namespace App\Foreground\Domain\Service\Local;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Put\Item;

class LocalCoordinator
{
    public function handleWriteLocally(Item $item): Node
    {
        dd('todo');
    }
}
