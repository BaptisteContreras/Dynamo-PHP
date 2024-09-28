<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

abstract class RingMergeTestCase extends TestCase
{
    protected const NODE_1 = '01900395-93bc-72a6-bf1b-0b66e93311ca';
    protected const NODE_2 = '01901353-ceb7-71ba-a89c-794a45e538ce';
    protected const NODE_3 = '01909e05-33dc-7c3e-a131-32a829453bd4';

    protected const VIRTUAL_NODE_1 = '01901352-03dc-7cdf-8bae-46478df51aeb';
    protected const VIRTUAL_NODE_2 = '01901352-03dc-7cdf-8bae-46478df51aec';
    protected const VIRTUAL_NODE_3 = '01909912-05b9-76c9-953a-0b0bbb5d858d';
    protected const VIRTUAL_NODE_4 = '01909912-e139-72da-98d5-c39667a6f652';
    protected const VIRTUAL_NODE_5 = '01909912-f8b0-7c9b-bced-c3bb64b00e0f';
    protected const VIRTUAL_NODE_6 = '01909e0f-3899-779f-8093-0473397e8ea0';
    protected const VIRTUAL_NODE_7 = '01909e1d-ffa3-7405-ae38-20bc09a31247';
    protected const VIRTUAL_NODE_8 = '01909e1e-7c18-76e5-b50a-ccc873b9ccef';

    protected function createRingFromDataProvider(array $nodesData): Ring
    {
        return new Ring(new NodeCollection(
            array_map(fn (array $nodeData) => $this->dataArrayToNode($nodeData), $nodesData)
        ));
    }

    protected function dataArrayToNode(array $nodeData): Node
    {
        $virtualNodes = VirtualNodeCollection::createEmpty();

        $node = new Node(
            UuidV7::fromString($nodeData['id']),
            $nodeData['host'],
            $nodeData['networkPort'],
            $nodeData['membershipState'],
            $nodeData['joinedAt'],
            $nodeData['weight'],
            $nodeData['seed'],
            $nodeData['updatedAt'],
            $nodeData['label'],
            $nodeData['localNodeState'],
            $virtualNodes,
            $nodeData['local']
        );

        $virtualNodes->merge(
            new VirtualNodeCollection(
                array_map(fn (array $virtualNodeData) => $this->dataArrayToVirtualNode($virtualNodeData, $node), $nodeData['virtualNodes'])
            )
        );

        return $node;
    }

    protected function dataArrayToVirtualNode(array $virtualNodeData, Node $node): VirtualNode
    {
        return new VirtualNode(
            UuidV7::fromString($virtualNodeData['id']),
            $virtualNodeData['label'],
            $virtualNodeData['slot'],
            $virtualNodeData['createdAt'],
            $node,
            $virtualNodeData['active']
        );
    }

    protected function assertNodeIsAsExpected(array $expectedValues, Node $node): void
    {
        self::assertEquals($expectedValues['id'], $node->getStringId());
        self::assertEquals($expectedValues['host'], $node->getHost());
        self::assertEquals($expectedValues['networkPort'], $node->getNetworkPort());
        self::assertEquals($expectedValues['membershipState'], $node->getMembershipState());
        self::assertEquals($expectedValues['joinedAt'], $node->getJoinedAt());
        self::assertEquals($expectedValues['weight'], $node->getWeight());
        self::assertEquals($expectedValues['seed'], $node->isSeed());
        self::assertEquals($expectedValues['localNodeState'], $node->getLocalNodeState());
        //        self::assertEquals($expectedValues['updatedAt'], $node->getUpdatedAt());
        self::assertEquals($expectedValues['label'], $node->getLabel());
        self::assertEquals($expectedValues['local'], $node->isLocal());
    }
}
