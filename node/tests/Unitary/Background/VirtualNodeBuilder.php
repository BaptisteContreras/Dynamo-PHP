<?php

namespace App\Tests\Unitary\Background;

use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use Symfony\Component\Uid\UuidV7;

final class VirtualNodeBuilder
{
    public function __construct(
        private readonly int $slot,
        private readonly bool $isActive
    ) {
    }

    public static function createVirtualNode(int $slot, bool $isActive = true): self
    {
        return new self($slot, $isActive);
    }

    public function getVirtualNode(Node $node): VirtualNode
    {
        return new VirtualNode(
            new UuidV7(),
            sprintf('%s-%s', $node->getLabel(), rand()),
            $this->slot,
            new \DateTimeImmutable(),
            $node,
            $this->isActive
        );
    }
}
