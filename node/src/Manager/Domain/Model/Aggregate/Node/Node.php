<?php

namespace App\Manager\Domain\Model\Aggregate\Node;

use App\Manager\Domain\Model\Aggregate\Node\Collection\RoVirtualNodeCollection;
use App\Manager\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Shared\Domain\Const\NodeState;
use Symfony\Component\Uid\UuidV7;

final class Node
{
    /**
     * @param positive-int $weight
     */
    public function __construct(
        private readonly UuidV7 $id,
        private readonly string $host,
        private readonly int $networkPort,
        private NodeState $state,
        private readonly \DateTimeImmutable $joinedAt,
        private int $weight,
        private readonly bool $selfEntry,
        private readonly bool $seed,
        private readonly string $label,
        private VirtualNodeCollection $virtualNodes
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getState(): NodeState
    {
        return $this->state;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    /**
     * @return positive-int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param positive-int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    public function isSelfEntry(): bool
    {
        return $this->selfEntry;
    }

    public function isSeed(): bool
    {
        return $this->seed;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setState(NodeState $state): void
    {
        $this->state = $state;
    }

    public function getVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->virtualNodes;
    }

    public function addVirtualNode(string $subLabel, int $slot): static
    {
        $this->virtualNodes->add(
            new VirtualNode(
                $subLabel,
                $slot,
                $this
            )
        );

        return $this;
    }

    public function addExistingVirtualNode(VirtualNode $virtualNode): static
    {
        if (!$this->hasSameId($virtualNode->getNode())) {
            throw new \LogicException(sprintf('Virtual node %s is already owned by node %s', $virtualNode->getId()->toRfc4122(), $this->getId()->toRfc4122()));
        }

        $this->virtualNodes->add($virtualNode);

        return $this;
    }

    public function hasSameId(self $node): bool
    {
        return $this->id->equals($node->getId());
    }

    /**
     * @return array<string>
     */
    public function getRemovedVirtualNodes(): array
    {
        return $this->virtualNodes->getRemovedElements();
    }
}
