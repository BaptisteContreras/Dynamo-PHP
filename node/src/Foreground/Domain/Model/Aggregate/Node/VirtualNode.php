<?php

namespace App\Foreground\Domain\Model\Aggregate\Node;

use Symfony\Component\Uid\UuidV7;

final readonly class VirtualNode
{
    public function __construct(
        private string $subLabel,
        private int $slot,
        private Node $node,
        private bool $active,
        private UuidV7 $id
    ) {
    }

    public function getSubLabel(): string
    {
        return $this->subLabel;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }
}
