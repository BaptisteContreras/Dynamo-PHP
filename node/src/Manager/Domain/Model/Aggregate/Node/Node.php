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
        private NodeState $membershipState,
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

    public function getMembershipState(): NodeState
    {
        return $this->membershipState;
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

    public function setMembershipState(NodeState $membershipState): void
    {
        $this->membershipState = $membershipState;
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

    public function getStringId(): string
    {
        return $this->id->toRfc4122();
    }
}
