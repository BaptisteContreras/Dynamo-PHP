<?php

namespace App\Tests\Unitary\Background\Domain\Ring;

use App\Background\Domain\Model\Aggregate\History\History;
use App\Shared\Domain\Const\MembershipState;

class RingMergePhase3Test extends RingMergeTestCase
{
    public static function getSimpleTestCases(): \Generator
    {
        yield '[Simple] already disabled virtual node stay disabled' => [
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
                            'active' => false,
                        ],
                    ],
                ],
                self::NODE_3 => [
                    'id' => self::NODE_3,
                    'host' => '127.0.0.1',
                    'networkPort' => 8099,
                    'membershipState' => MembershipState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => true,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'A1',
                            'slot' => 3321,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-06-10 21:28:00'),
                            'active' => false,
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
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'B1',
                            'slot' => 500,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                            'active' => false,
                        ],
                    ],
                ],
            ],
            [
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_4,
            ],
        ];
        yield '[Simple] ensure that virtual node that should be disabled really are' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'membershipState' => MembershipState::LEAVING,
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
                self::NODE_2 => [
                    'id' => self::NODE_2,
                    'host' => 'localhost',
                    'networkPort' => 8080,
                    'membershipState' => MembershipState::LEAVING,
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
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'B1',
                            'slot' => 500,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-12 21:28:00'),
                            'active' => false,
                        ],
                    ],
                ],
                self::NODE_3 => [
                    'id' => self::NODE_3,
                    'host' => '127.0.0.1',
                    'networkPort' => 8099,
                    'membershipState' => MembershipState::LEAVING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => true,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'A1',
                            'slot' => 3321,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2025-06-10 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
            ],
            [
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_4,
            ],
        ];
    }

    public static function getSimpleSlotConflictTestCases(): \Generator
    {
        yield '[SimpleSlotConflict] slot conflict resolution' => [
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
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'A3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-11 21:28:00'),
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
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'B2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-14 21:28:00'),
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_6,
                            'label' => 'B3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2032-06-14 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_5,
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_6,
            ],
        ];
        yield '[SimpleSlotConflict] slot conflict resolution with local node does not change anything' => [
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
                    'local' => true,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'A3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-11 21:28:00'),
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
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'B2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-14 21:28:00'),
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_6,
                            'label' => 'B3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2032-06-14 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_5,
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_6,
            ],
        ];
        yield '[SimpleSlotConflict] slot conflict resolution with leaving node' => [
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
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'A3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-11 21:28:00'),
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
                    'membershipState' => MembershipState::LEAVING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'B2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-14 21:28:00'),
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_6,
                            'label' => 'B3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2032-06-14 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_5,
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_6,
            ],
        ];
        yield '[SimpleSlotConflict] slot conflict resolution with local node leaving' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'membershipState' => MembershipState::LEAVING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => true,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'A3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-11 21:28:00'),
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
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'B2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-14 21:28:00'),
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_6,
                            'label' => 'B3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2032-06-14 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_6,
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_5,
            ],
        ];
    }

    public static function getAdvancedSlotConflictTestCases(): \Generator
    {
        yield '[AdvancedSlotConflict] all virtual nodes claim the same slot' => [
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => '127.0.0.1',
                    'networkPort' => 80,
                    'membershipState' => MembershipState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-11 21:28:00'),
                    'label' => 'A',
                    'local' => true,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_1,
                            'label' => 'A1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-14 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'A3',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-12 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
                self::NODE_3 => [
                    'id' => self::NODE_3,
                    'host' => '127.0.0.1',
                    'networkPort' => 8880,
                    'membershipState' => MembershipState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2034-06-10 21:28:00'),
                    'weight' => 1,
                    'seed' => true,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2034-06-11 21:28:00'),
                    'label' => 'C',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_8,
                            'label' => 'C2',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2094-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_7,
                            'label' => 'C1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2014-06-10 21:28:00'),
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
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'B2',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-14 21:28:00'),
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_6,
                            'label' => 'B3',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2032-06-15 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_8,
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_5,
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_6,
                self::VIRTUAL_NODE_7,
            ],
        ];
    }

    public static function getSimpleSlotConflictWithSameNodeTestCases(): \Generator
    {
        yield '[SimpleSlotConflictWithSameNode] slot conflict resolution' => [
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
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-10 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_3,
                            'label' => 'A2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-06-11 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_5,
                            'label' => 'A3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2044-06-11 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::NODE_1 => [
                    'id' => self::NODE_1,
                    'host' => 'localhost',
                    'networkPort' => 8080,
                    'membershipState' => MembershipState::JOINING,
                    'joinedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'weight' => 9,
                    'seed' => false,
                    'updatedAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                    'label' => 'B',
                    'local' => false,
                    'virtualNodes' => [
                        [
                            'id' => self::VIRTUAL_NODE_2,
                            'label' => 'B1',
                            'slot' => 1,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-12 21:28:00'),
                            'active' => true,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_4,
                            'label' => 'B2',
                            'slot' => 2,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2030-06-14 21:28:00'),
                            'active' => false,
                        ],
                        [
                            'id' => self::VIRTUAL_NODE_6,
                            'label' => 'B3',
                            'slot' => 3,
                            'createdAt' => \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2032-06-14 21:28:00'),
                            'active' => true,
                        ],
                    ],
                ],
            ],
            [
                self::VIRTUAL_NODE_2,
                self::VIRTUAL_NODE_3,
                self::VIRTUAL_NODE_5,
            ],
            [
                self::VIRTUAL_NODE_1,
                self::VIRTUAL_NODE_4,
                self::VIRTUAL_NODE_6,
            ],
        ];
    }

    /**
     * @dataProvider getSimpleTestCases
     * @dataProvider getAdvancedSlotConflictTestCases
     * @dataProvider getSimpleSlotConflictWithSameNodeTestCases
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
