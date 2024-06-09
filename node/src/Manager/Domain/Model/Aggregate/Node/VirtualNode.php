<?php

namespace App\Manager\Domain\Model\Aggregate\Node;

use Symfony\Component\Uid\UuidV7;

final class VirtualNode
{
    public function __construct(
        private readonly string $subLabel,
        private readonly int $slot,
        private readonly Node $node,
        private bool $active = true,
        private readonly \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
        private readonly UuidV7 $id = new UuidV7()
    ) {
    }

    public function getSubLabel(): string
    {
        return $this->subLabel;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
