<?php

namespace App\Manager\Application\Query\Ring\Response;

use App\Manager\Domain\Model\Aggregate\Node\Node;

class SuccessResponse extends RingResponse
{
    public function __construct(private Node $node)
    {
    }

    public function getNode(): Node
    {
        return $this->node;
    }
}
