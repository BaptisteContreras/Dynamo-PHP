<?php

namespace App\Foreground\Domain\Model\Aggregate\Node;

use App\Foreground\Domain\Model\Aggregate\Node\Collection\RoVirtualNodeCollection;
use App\Foreground\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Shared\Domain\Const\MembershipState;
use App\Shared\Domain\Const\NodeState;
use Symfony\Component\Uid\UuidV7;

final readonly class Node
{
    public function __construct(
        private UuidV7 $id,
        private string $host,
        private int $networkPort,
        private MembershipState $membershipState,
        private bool $selfEntry,
        private string $label,
        private NodeState $localNodeState,
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

    public function getMembershipState(): MembershipState
    {
        return $this->membershipState;
    }

    public function isSelfEntry(): bool
    {
        return $this->selfEntry;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getLocalNodeState(): NodeState
    {
        return $this->localNodeState;
    }

    public function getVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->virtualNodes;
    }
}
