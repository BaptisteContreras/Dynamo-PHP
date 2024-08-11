<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use Symfony\Component\Uid\UuidV7;

class NodeRange
{
    public function __construct(
        private readonly int $startRange,
        private readonly int $endRange,
        private readonly Node $node,
    ) {
    }

    public function getStartRange(): int
    {
        return $this->startRange;
    }

    public function getEndRange(): int
    {
        return $this->endRange;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getNodeId(): UuidV7
    {
        return $this->node->getId();
    }

    public function getNodeStringId(): string
    {
        return $this->node->getId()->toRfc4122();
    }
}
