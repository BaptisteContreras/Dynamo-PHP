<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Shared\Domain\Const\MembershipState;

class RingMergePhase2Test extends RingMergeTestCase
{
    public static function getSimpleTestCases(): \Generator
    {
        yield '[Simple] merge two empty ring' => [
            [],
            [],
        ];
        yield '[Simple] merge two different node' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'membershipState' => MembershipState::JOINING,
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
                    'membershipState' => MembershipState::JOINING,
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
        ];
        yield '[Simple] merge same node' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'membershipState' => MembershipState::JOINING,
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
                    'membershipState' => MembershipState::JOINING,
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
        ];
    }

    /**
     * @dataProvider getSimpleTestCases
     */
    public function testSuccessful(
        array $localRingNodes,
        array $remoteRingNodes,
    ): void {
        $localRing = $this->createRingFromDataProvider($localRingNodes);
        $remoteRing = $this->createRingFromDataProvider($remoteRingNodes);

        $expectedNodesId = array_unique(array_merge(array_keys($localRingNodes), array_keys($remoteRingNodes)));

        $history = $this->createMock(History::class);
        $history
            ->expects(self::exactly(count($expectedNodesId)))
            ->method('applyEventsForNode');

        $localRing->merge($remoteRing, $history);
    }
}
