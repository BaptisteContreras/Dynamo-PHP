<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Collection\VirtualNodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Shared\Domain\Const\NodeState;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

class RingMergeTest extends TestCase
{
    private const NODE_1 = '01900395-93bc-72a6-bf1b-0b66e93311ca';
    private const NODE_2 = '01901353-ceb7-71ba-a89c-794a45e538ce';

    private const VIRTUAL_NODE_1 = '01901352-03dc-7cdf-8bae-46478df51aeb';
    private const VIRTUAL_NODE_2 = '01901352-03dc-7cdf-8bae-46478df51aec';
    private const VIRTUAL_NODE_3 = '01909912-05b9-76c9-953a-0b0bbb5d858d';
    private const VIRTUAL_NODE_4 = '01909912-e139-72da-98d5-c39667a6f652';
    private const VIRTUAL_NODE_5 = '01909912-f8b0-7c9b-bced-c3bb64b00e0f';

    public static function getSimpleTestCases(): \Generator
    {
        yield '[Simple] merge two empty ring' => [
            [],
            [],
            [],
            [],
        ];
        yield '[Simple] merge two different node' => [
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
    }

    public static function getLocalRingPrecedenceTestCases(): \Generator
    {
        yield '[LocalRingPrecedence] merge same node and remote virtual node is not active' => [
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
                            'active' => true, // <- local is active
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
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-10 21:28:00'),
                            'active' => false, // <- local is not
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
        yield '[LocalRingPrecedence] merge same node and local virtual node is not active' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-10 21:28:00'),
                            'active' => false, // <- local is not active
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
                            'active' => true, // <- remote is active
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
        yield '[LocalRingPrecedence] merge same node and remote node has been updated more recently' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 200,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-10 21:28:00'),
                            'active' => false, // <- local is not active
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 300,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-06-11 21:28:00'),
                            'active' => false, // <- local is not active
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'A3',
                            'slot' => 400,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-04-11 21:28:00'),
                            'active' => true, // <- local is active
                        ],
                    ],
                ],
            ],
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => 'localhost',
                    'networkPort' => 8080,
                    'state' => NodeState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 99,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'B1',
                            'slot' => 99,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true, // <- remote is active
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B2',
                            'slot' => 9999,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-07-10 21:28:00'),
                            'active' => true, // <- remote is active
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'B3',
                            'slot' => 3333,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-08-10 21:28:00'),
                            'active' => true, // <- remote is active
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_5,
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_3,
            ],
        ];
    }

    public static function getLocalNodeTestCases(): \Generator
    {
    }

    /**
     * @dataProvider getSimpleTestCases
     * @dataProvider getLocalRingPrecedenceTestCases
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

        self::assertCount(count($expectedNodesId), $localNodes, 'expected total nodes mismatch');
        self::assertCount(count($internalRing), $localVirtualNodes, 'expected internal ring mismatch');
        self::assertCount(count($disabledVirtualNodes), $localDisabledVirtualNodes, 'expected disabled virtual nodes mismatch');

        foreach ($internalRing as $expectActiveVirtualNodeId) {
            self::assertTrue($localVirtualNodes->keyExists($expectActiveVirtualNodeId), 'expected virtual node is not in the ring');
        }

        foreach ($disabledVirtualNodes as $expectDisabledVirtualNodeId) {
            self::assertTrue($localDisabledVirtualNodes->keyExists($expectDisabledVirtualNodeId), 'expected virtual node is not disabled');
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
