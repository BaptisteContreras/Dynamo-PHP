<?php

namespace App\Foreground\Domain\Service\Forward;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Model\Const\ForwardResult;

interface ForwardStrategyInterface
{
    public function forwardItemWriteTo(Node $node, Item $item): ForwardResult;
}
