<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use App\Background\Domain\Model\Aggregate\History\HistoryTimeline;
use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoVirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use Symfony\Component\Uid\UuidV7;

final class Ring
{
    private readonly VirtualNodeCollection $virtualNodeCollection;
    private VirtualNodeCollection $internalRing;
    private VirtualNodeCollection $disabledVirtualNodeCollection;

    public function __construct(
        private NodeCollection $nodeCollection = new NodeCollection(),
    ) {
        $this->virtualNodeCollection = VirtualNodeCollection::createEmpty();
        $this->internalRing = VirtualNodeCollection::createEmpty();
        $this->disabledVirtualNodeCollection = VirtualNodeCollection::createEmpty();

        $this->updateVirtualNodeCollection();
        $this->updateInternalRing();
    }

    public function getNodes(): RoNodeCollection
    {
        return $this->nodeCollection;
    }

    public function addNode(Node $node): self
    {
        $this->nodeCollection->add($node);
        $this->virtualNodeCollection->merge($node->getVirtualNodeCollection());

        $this->updateInternalRing();

        return $this;
    }

    public function getVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->internalRing;
    }

    public function merge(Ring $otherRing, HistoryTimeline $historyTimeline): self
    {
        $newNodeCollection = NodeCollection::createEmpty();

        foreach ($this->nodeCollection as $currentNode) {
            if ($currentNode->isLocal() || !$otherRing->hasNode($currentNode->getId())) {
                // The local node always have the freshest information about itself

                $newNodeCollection->add(clone $currentNode);

                continue;
            }

            $newNodeCollection->add($this->mergeNodes(
                $currentNode,
                $otherRing->getNode($currentNode->getId())
            ));
        }

        foreach ($otherRing->getNodes() as $otherNode) {
            if (!$this->hasNode($otherNode->getId())) {
                $newNodeCollection->add(clone $otherNode);
            }
        }

        $this->nodeCollection = $newNodeCollection;

        $this->updateNodesWithHistory($historyTimeline);
        $this->updateVirtualNodeCollection();
        $this->updateInternalRing();

        return $this;
    }

    public function hasNode(UuidV7|string $nodeId): bool
    {
        if ($nodeId instanceof UuidV7) {
            $nodeId = $nodeId->toRfc4122();
        }

        return $this->nodeCollection->keyExists($nodeId);
    }

    public function getNode(UuidV7|string $nodeId): Node
    {
        if ($nodeId instanceof UuidV7) {
            $nodeId = $nodeId->toRfc4122();
        }

        return $this->nodeCollection->get($nodeId) ?: throw new \LogicException('Node not found');
    }

    public function hasFresherNodeVersion(Node $node): bool
    {
        /** @var ?Node $localNodeVersion */
        $localNodeVersion = $this->nodeCollection->get($node->getStringId());

        return true === $localNodeVersion?->isFresherThan($node);
    }

    private function updateNodesWithHistory(HistoryTimeline $historyTimeline): void
    {
        foreach ($historyTimeline->getNewEvents() as $newEvent) {
            $this->getNode($newEvent->getNode())->applyEvent($newEvent);
        }
    }

    private function mergeNodes(Node $node, Node $otherNode): Node
    {
        $mergedVirtualNodeCollection = VirtualNodeCollection::createEmpty();

        $mergedNode = new Node(
            $node->getId(),
            $node->getHost(),
            $node->getNetworkPort(),
            $node->getState(),
            $node->getJoinedAt(),
            $node->getWeight(),
            $node->isSeed(),
            new \DateTimeImmutable(),
            $node->getLabel(),
            $mergedVirtualNodeCollection
        );

        $mergedVirtualNodeCollection
            ->merge($node->getVirtualNodeCollection())
            ->merge($otherNode->getVirtualNodeCollection());

        return $mergedNode;
    }

    private function updateVirtualNodeCollection(): void
    {
        foreach ($this->nodeCollection as $node) {
            $this->virtualNodeCollection->merge($node->getVirtualNodeCollection());
        }
    }

    private function updateInternalRing(): void
    {
        $this->internalRing = VirtualNodeCollection::createEmpty();
        $this->disabledVirtualNodeCollection = VirtualNodeCollection::createEmpty();

        /** @var array<int, VirtualNode> $slotMap */
        $slotMap = [];

        foreach ($this->virtualNodeCollection as $virtualNode) {
            if (!$virtualNode->isActive()) {
                $this->disabledVirtualNodeCollection->add($virtualNode);

                continue;
            }

            if ($virtualNode->shouldBeDisabled()) {
                $virtualNode->disable();
                $this->disabledVirtualNodeCollection->add($virtualNode);

                continue;
            }

            if ($alreadyAssignedVirtualNode = ($slotMap[$virtualNode->getSlot()] ?? null)) {
                if ($alreadyAssignedVirtualNode->isNewerThan($virtualNode)) {
                    $this->disabledVirtualNodeCollection->add($virtualNode);
                    $virtualNode->disable();

                    continue;
                }

                $this->internalRing->remove($alreadyAssignedVirtualNode);
                $this->disabledVirtualNodeCollection->add($alreadyAssignedVirtualNode);
                $alreadyAssignedVirtualNode->disable();
            }

            $this->internalRing->add($virtualNode);
            $slotMap[$virtualNode->getSlot()] = $virtualNode;
        }
    }
}
