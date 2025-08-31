<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Exception\CannotHandleWriteException;
use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Model\Aggregate\Node\Node;

interface PutCoordinatorInterface
{
    /**
     * @return Node that handled the write
     *
     * @throws CannotHandleWriteException
     */
    public function handleWrite(Item $item): Node;
}
