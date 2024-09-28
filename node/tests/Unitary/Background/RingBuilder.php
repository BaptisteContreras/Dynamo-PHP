<?php

namespace App\Tests\Unitary\Background;

use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Shared\Domain\Const\MembershipState;
use Symfony\Component\Uid\UuidV7;

final class RingBuilder
{
    private readonly Ring $ring;

    public function __construct()
    {
        $this->ring = new Ring();
    }

    public static function createRing(): self
    {
        return new self();
    }

    public function addActiveNode(string $nodeId, VirtualNodeBuilder ...$virtualNodes): self
    {
        return $this->addNode($nodeId, MembershipState::JOINED, ...$virtualNodes);
    }

    public function addNode(string $nodeId, MembershipState $nodeState, VirtualNodeBuilder ...$virtualNodes): self
    {
        $virtualNodesCollection = VirtualNodeCollection::createEmpty();

        $node = new Node(
            UuidV7::fromString($nodeId),
            '127.0.0.1',
            (string) rand(80, 65000),
            $nodeState,
            new \DateTimeImmutable(),
            count($virtualNodes),
            false,
            new \DateTimeImmutable(),
            sprintf('LABEL_%s', rand()),
            $virtualNodesCollection,
            false
        );

        $virtualNodesCollection->merge(
            new VirtualNodeCollection(
                array_map(fn (VirtualNodeBuilder $virtualNodeBuilder) => $virtualNodeBuilder->getVirtualNode($node), $virtualNodes)
            )
        );

        $this->ring->addNode($node);

        return $this;
    }

    public function getRing(): Ring
    {
        return $this->ring;
    }
}
