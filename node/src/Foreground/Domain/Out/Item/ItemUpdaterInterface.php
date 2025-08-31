<?php

namespace App\Foreground\Domain\Out\Item;

use App\Foreground\Domain\Model\Aggregate\Item\Item;

interface ItemUpdaterInterface
{
    public function saveItem(Item $item): void;
}
