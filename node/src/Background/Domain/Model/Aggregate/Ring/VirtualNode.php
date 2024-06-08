<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use Symfony\Component\Uid\UuidV7;

final readonly class VirtualNode
{
    public function __construct(
        private UuidV7 $id,
        private string $label,
        private int $slot,
        private \DateTimeImmutable $createdAt,
        private Node $node
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

    public function isNewerThan(self $otherVirtualNode): bool
    {
        return $this->createdAt >= $otherVirtualNode->getCreatedAt();
    }
}
