<?php

namespace App\Manager\Application\Command\Join\Response;

use App\Manager\Domain\Model\Aggregate\Node\Node;

class SuccessResponse extends JoinResponse
{
    public function __construct(private Node $node)
    {
    }

    public function getNode(): Node
    {
        return $this->node;
    }
}
