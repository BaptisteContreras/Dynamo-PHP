<?php

namespace App\Foreground\Domain\Service\Local;

use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Out\Item\ItemUpdaterInterface;

class LocalCoordinator
{
    public function __construct(private readonly ItemUpdaterInterface $itemUpdater)
    {
    }

    public function handleWriteLocally(Item $item): Node
    {
        $item->incrementVersion();

        $this->itemUpdater->saveItem($item);

        // TODO propagate write to W replicas
        dd($item);
    }
}
