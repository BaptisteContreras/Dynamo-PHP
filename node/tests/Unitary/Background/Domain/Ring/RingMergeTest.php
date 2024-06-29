<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Shared\Domain\Const\NodeState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

class RingMergeTest extends TestCase
{
    private const NODE_1 = '01900395-93bc-72a6-bf1b-0b66e93311ca';
    private const NODE_2 = '01901353-ceb7-71ba-a89c-794a45e538ce';

    private const VIRTUAL_NODE_1 = '01901352-03dc-7cdf-8bae-46478df51aeb';
    private const VIRTUAL_NODE_2 = '01901352-03dc-7cdf-8bae-46478df51aec';

    public static function getData(): \Generator
    {
        yield 'simple merge' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::NODE_2 => [
                    'id' => self::NODE_2,
                    'host' => 'localhost',
                    'networkPort' => 8080,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 50,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_2,
            ],
            [
            ],
        ];
        yield 'merge same node' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => false,
                        ],
                    ],
                ],
            ],
            [
            ],
            [
                self::VIRTUAL_NODE_1,
            ],
        ];
    }

    /**
     * @dataProvider getData
     */
    public function testSuccessful(
        array $localRingNodes,
        array $remoteRingNodes,
        array $internalRing,
        array $disabledVirtualNodes
    ): void {
        $localRing = $this->createRingFromDataProvider($localRingNodes);
        $remoteRing = $this->createRingFromDataProvider($remoteRingNodes);

        $localRing->merge($remoteRing, History::createEmpty());

        $localNodes = $localRing->getNodes();
        $localVirtualNodes = $localRing->getVirtualNodes();
        $localDisabledVirtualNodes = $localRing->getDisabledVirtualNodes();

        $expectedNodesId = array_unique(array_merge(array_keys($localRingNodes), array_keys($remoteRingNodes)));

        self::assertCount(count($expectedNodesId), $localNodes);
        self::assertCount(count($internalRing), $localVirtualNodes);
        self::assertCount(count($disabledVirtualNodes), $localDisabledVirtualNodes);
        self::assertCount(count($disabledVirtualNodes), $localDisabledVirtualNodes);

        foreach ($internalRing as $expectActiveVirtualNodeId) {
            self::assertTrue($localVirtualNodes->keyExists($expectActiveVirtualNodeId));
        }

        foreach ($disabledVirtualNodes as $expectDisabledVirtualNodeId) {
            self::assertTrue($localDisabledVirtualNodes->keyExists($expectDisabledVirtualNodeId));
        }

        foreach ($localNodes as $node) {
            $expectedNodeValues = $localRingNodes[$node->getStringId()] ?? $remoteRingNodes[$node->getStringId()];

            $this->assertNodeIsAsExpected(
                $expectedNodeValues,
                $node
            );
        }
    }

    private function createRingFromDataProvider(array $nodesData): Ring
    {
        return new Ring(new NodeCollection(
            array_map(fn (array $nodeData) => $this->dataArrayToNode($nodeData), $nodesData)
        ));
    }

    private function dataArrayToNode(array $nodeData): Node
    {
        $virtualNodes = VirtualNodeCollection::createEmpty();

        $node = new Node(
            UuidV7::fromString($nodeData['id']),
            $nodeData['host'],
            $nodeData['networkPort'],
            $nodeData['state'],
            $nodeData['joinedAt'],
            $nodeData['weight'],
            $nodeData['seed'],
            $nodeData['updatedAt'],
            $nodeData['label'],
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

    private function dataArrayToVirtualNode(array $virtualNodeData, Node $node): VirtualNode
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

    private function assertNodeIsAsExpected(array $expectedValues, Node $node): void
    {
        self::assertEquals($expectedValues['id'], $node->getStringId());
        self::assertEquals($expectedValues['host'], $node->getHost());
        self::assertEquals($expectedValues['networkPort'], $node->getNetworkPort());
        self::assertEquals($expectedValues['state'], $node->getState());
        self::assertEquals($expectedValues['joinedAt'], $node->getJoinedAt());
        self::assertEquals($expectedValues['weight'], $node->getWeight());
        self::assertEquals($expectedValues['seed'], $node->isSeed());
        //        self::assertEquals($expectedValues['updatedAt'], $node->getUpdatedAt());
        self::assertEquals($expectedValues['label'], $node->getLabel());
        self::assertEquals($expectedValues['local'], $node->isLocal());
    }
}
