<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Shared\Domain\Const\NodeState;

class RingMergePhase1Test extends RingMergeTestCase
{
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
        yield '[LocalNode] merge same node and totally ignore remote node as local node is marked as "local"' => [
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
                    'local' => true,
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
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'A22',
                            'slot' => 1115,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-07-10 21:28:00'),
                            'active' => true, // <- local is not active
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
                            'active' => false, // <- remote is active
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
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_3,
            ],
        ];
    }

    /**
     * @dataProvider getSimpleTestCases
     * @dataProvider getLocalRingPrecedenceTestCases
     * @dataProvider getLocalNodeTestCases
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
}
