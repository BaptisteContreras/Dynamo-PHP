<?php

namespace App\Background\Domain\Model\Aggregate\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\RoVirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use Symfony\Component\Uid\UuidV7;

use function DeepCopy\deep_copy;

final class Ring
{
    private VirtualNodeCollection $virtualNodeCollection;
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
        $this->virtualNodeCollection->merge($node->getVirtualNodes());

        $this->updateInternalRing();

        return $this;
    }

    public function getVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->internalRing;
    }

    public function getDisabledVirtualNodes(): RoVirtualNodeCollection
    {
        return $this->disabledVirtualNodeCollection;
    }

    /**
     * When merging two rings we have to distinguish the "local ring" and the "remote ring".
     * The local ring is the current ring of the node.
     * The remote ring is the received ring.
     *
     * The process of merging two rings involves 3 steps :
     * - 1) Create a new node collection
     * - 2) Update all nodes of the new collection with the received history
     * - 3) Update status of virtual nodes and put them in the appropriate collection (ring or unused)
     *
     * During the first step, nodes in the local ring take precedence over their counterpart in the remote ring.
     * This means that values of nodes in the local ring are used even if they are older. (They are updated later with the history in step 2)
     *
     * When a node exists in both rings, its virtual nodes are merged in a new collection.
     * Values of the virtual node in the local ring the precedence over the remote one except for the "active" field.
     * As virtual node cannot be reactivated once disabled, "active" => false take precedence, even over the local virtual node value.
     *
     * There is one exception for node marked as "local". (note that only node in local ring can be marked as local)
     * $node->isLocal() === true means that this is the information of the node executing this algorithm.
     * We consider that nodes always have the more exact values about themselves and never update their values.
     * -> local node are never updated nor their virtual nodes
     */
    public function merge(Ring $otherRing, History $historyTimeline): self
    {
        $newNodeCollection = NodeCollection::createEmpty();

        foreach ($this->nodeCollection as $currentNode) {
            if ($currentNode->isLocal() || !$otherRing->hasNode($currentNode->getId())) {
                $newNodeCollection->add(deep_copy($currentNode));

                continue;
            }

            $newNodeCollection->add($this->mergeNodes(
                $currentNode,
                $otherRing->getNode($currentNode->getId())
            ));
        }

        foreach ($otherRing->getNodes() as $otherNode) {
            if (!$this->hasNode($otherNode->getId())) {
                $newNodeCollection->add(deep_copy($otherNode));
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

    /**
     * @return array<NodeRange>
     */
    public function getNodeSlotRanges(): array
    {
        if ($this->internalRing->isEmpty()) {
            return [];
        }

        $result = [];

        /** @var VirtualNode $firstVirtualNode */
        $firstVirtualNode = $this->internalRing->first();
        $currentStartRange = $firstVirtualNode->getSlot();
        $currentNode = $firstVirtualNode->getNode();

        foreach ($this->internalRing as $virtualNode) {
            if (!$virtualNode->getNodeId()->equals($currentNode->getId())) {
                $result[] = new NodeRange(
                    $currentStartRange,
                    $virtualNode->getSlot() - 1,
                    $currentNode
                );

                $currentStartRange = $virtualNode->getSlot();
                $currentNode = $virtualNode->getNode();
            }
        }

        $result[] = new NodeRange(
            $currentStartRange,
            $firstVirtualNode->getSlot() - 1,
            $currentNode
        );

        return $result;
    }

    public function hasFresherNodeVersion(Node $node): bool
    {
        /** @var ?Node $localNodeVersion */
        $localNodeVersion = $this->nodeCollection->get($node->getStringId());

        return true === $localNodeVersion?->isFresherThan($node);
    }

    private function updateNodesWithHistory(History $history): void
    {
        foreach ($this->nodeCollection as $node) {
            if (!$node->isLocal()) {
                // local node should not be updated as it is always considered fresh
                $history->applyEventsForNode($node);
            }
        }
    }

    private function mergeNodes(Node $localNode, Node $otherNode): Node
    {
        $mergedVirtualNodeCollection = VirtualNodeCollection::createEmpty();

        $mergedNode = new Node(
            $localNode->getId(),
            $localNode->getHost(),
            $localNode->getNetworkPort(),
            $localNode->getState(),
            $localNode->getJoinedAt(),
            $localNode->getWeight(),
            $localNode->isSeed(),
            new \DateTimeImmutable(),
            $localNode->getLabel(),
            $mergedVirtualNodeCollection
        );

        foreach ($localNode->getVirtualNodes() as $virtualNode) {
            $mergedVirtualNodeCollection->add(VirtualNode::copyWithNewNode($virtualNode, $mergedNode));
        }

        foreach ($otherNode->getVirtualNodes() as $otherVirtualNode) {
            $alreadyMergedVirtualNode = $mergedVirtualNodeCollection->get($otherVirtualNode->getStringId());

            if (!$localNode->isLocal() && $alreadyMergedVirtualNode && !$otherVirtualNode->isActive()) {
                $alreadyMergedVirtualNode->disable();
            }

            if (!$alreadyMergedVirtualNode) {
                $mergedVirtualNodeCollection->add(VirtualNode::copyWithNewNode($otherVirtualNode, $mergedNode));
            }
        }

        return $mergedNode;
    }

    private function updateVirtualNodeCollection(): void
    {
        $this->virtualNodeCollection = VirtualNodeCollection::createEmpty();

        foreach ($this->nodeCollection as $node) {
            $this->virtualNodeCollection->merge($node->getVirtualNodes());
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

        $this->internalRing->sortBySlot();
    }
}
