<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use Symfony\Component\Uid\UuidV7;

final class VirtualNode
{
    public function __construct(
        private readonly UuidV7 $id,
        private readonly string $label,
        private readonly int $slot,
        private readonly \DateTimeImmutable $createdAt,
        private readonly Node $node,
        private bool $active = true // TODO change this
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function disable(): self
    {
        $this->active = false;

        return $this;
    }

    public function isNewerThan(self $otherVirtualNode): bool
    {
        return $this->createdAt >= $otherVirtualNode->getCreatedAt();
    }

    public function shouldBeDisabled(): bool
    {
        return $this->node->isLeavingRing();
    }
}
