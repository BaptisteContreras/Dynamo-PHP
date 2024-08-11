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
        private bool $active
    ) {
    }

    public static function copyWithNewNode(self $virtualNodeToCopy, Node $newNode): self
    {
        return new self(
            $virtualNodeToCopy->getId(),
            $virtualNodeToCopy->getLabel(),
            $virtualNodeToCopy->getSlot(),
            $virtualNodeToCopy->getCreatedAt(),
            $newNode,
            $virtualNodeToCopy->isActive(),
        );
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

    public function getNodeId(): UuidV7
    {
        return $this->node->getId();
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
        return $this->node->isLeavingRing() || $this->node->isJoiningError();
    }

    public function getStringId(): string
    {
        return $this->id->toRfc4122();
    }
}
