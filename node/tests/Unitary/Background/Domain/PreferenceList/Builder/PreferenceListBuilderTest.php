<?php

namespace App\Tests\Unitary\Background\Domain\PreferenceList\Builder;

use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Shared\Domain\Const\NodeState;
use App\Tests\Unitary\Background\RingBuilder;
use App\Tests\Unitary\Background\VirtualNodeBuilder;
use PHPUnit\Framework\TestCase;

class PreferenceListBuilderTest extends TestCase
{
    protected const NODE_1 = '01901352-03dc-7cdf-8bae-46478df51aeb';
    protected const NODE_2 = '01901352-03dc-7cdf-8bae-46478df51aec';
    protected const NODE_3 = '01909912-05b9-76c9-953a-0b0bbb5d858d';
    protected const NODE_4 = '01909912-e139-72da-98d5-c39667a6f652';
    protected const NODE_5 = '01909912-f8b0-7c9b-bced-c3bb64b00e0f';
    protected const NODE_6 = '01909e0f-3899-779f-8093-0473397e8ea0';
    protected const NODE_7 = '01909e1d-ffa3-7405-ae38-20bc09a31247';
    protected const NODE_8 = '01909e1e-7c18-76e5-b50a-ccc873b9ccef';

    protected const N_2 = 2;
    protected const N_3 = 3;

    public static function getSimpleTestCases(): \Generator
    {
        yield 'Only one node covers all' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                )
                ->getRing(),
            [
                '0-359' => [[self::NODE_1], []],
            ],
        ];
        yield 'Multiple nodes and N is greater than the total amount of nodes' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(150),
                )
                ->getRing(),
            [
                '1-149' => [[self::NODE_1, self::NODE_2], []],
                '150-0' => [[self::NODE_2, self::NODE_1], []],
            ],
        ];
        yield 'Multiple nodes and N is equals to the total amount of nodes' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(150),
                )
                ->addActiveNode(
                    self::NODE_3,
                    VirtualNodeBuilder::createVirtualNode(180),
                )
                ->getRing(),
            [
                '1-149' => [[self::NODE_1, self::NODE_2, self::NODE_3], []],
                '150-179' => [[self::NODE_2, self::NODE_3, self::NODE_1], []],
                '180-0' => [[self::NODE_3, self::NODE_1, self::NODE_2], []],
            ],
        ];
        yield 'Multiple nodes and N is smaller than the total amount of nodes' => [
            self::N_2,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(150),
                )
                ->addActiveNode(
                    self::NODE_3,
                    VirtualNodeBuilder::createVirtualNode(180),
                )
                ->addActiveNode(
                    self::NODE_4,
                    VirtualNodeBuilder::createVirtualNode(185),
                )
                ->getRing(),
            [
                '1-149' => [[self::NODE_1, self::NODE_2], [self::NODE_3, self::NODE_4]],
                '150-179' => [[self::NODE_2, self::NODE_3], [self::NODE_4, self::NODE_1]],
                '180-184' => [[self::NODE_3, self::NODE_4], [self::NODE_1, self::NODE_2]],
                '185-0' => [[self::NODE_4, self::NODE_1], [self::NODE_2, self::NODE_3]],
            ],
        ];
        yield 'Multiple nodes and virtual nodes' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                    VirtualNodeBuilder::createVirtualNode(150),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(75),
                    VirtualNodeBuilder::createVirtualNode(180),
                )
                ->getRing(),
            [
                '1-74' => [[self::NODE_1, self::NODE_2], []],
                '75-149' => [[self::NODE_2, self::NODE_1], []],
                '150-179' => [[self::NODE_1, self::NODE_2], []],
                '180-0' => [[self::NODE_2, self::NODE_1], []],
            ],
        ];
        yield 'Multiple nodes, multiple vitual nodes and N is smaller than the total amount of nodes' => [
            self::N_2,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                    VirtualNodeBuilder::createVirtualNode(30),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(150),
                    VirtualNodeBuilder::createVirtualNode(190),
                )
                ->addActiveNode(
                    self::NODE_3,
                    VirtualNodeBuilder::createVirtualNode(180),
                )
                ->addActiveNode(
                    self::NODE_4,
                    VirtualNodeBuilder::createVirtualNode(185),
                )
                ->getRing(),
            [
                '1-149' => [[self::NODE_1, self::NODE_2], [self::NODE_3, self::NODE_4]],
                '150-179' => [[self::NODE_2, self::NODE_3], [self::NODE_4, self::NODE_1]],
                '180-184' => [[self::NODE_3, self::NODE_4], [self::NODE_1, self::NODE_2]],
                '185-189' => [[self::NODE_4, self::NODE_2], [self::NODE_1, self::NODE_3]],
                '190-0' => [[self::NODE_2, self::NODE_1], [self::NODE_3, self::NODE_4]],
            ],
        ];
    }

    public static function getReplicationOnlyOnDistinctPhysicalNodeRuleTestCases(): \Generator
    {
        yield 'Two consecutive virtual nodes belongs to the same physical node ' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                    VirtualNodeBuilder::createVirtualNode(50),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(75),
                )
                ->getRing(),
            [
                '1-74' => [[self::NODE_1, self::NODE_2], []],
                '75-0' => [[self::NODE_2, self::NODE_1], []],
            ],
        ];
        yield 'Prevent multiple replications of the same range on a physical node' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(1),
                    VirtualNodeBuilder::createVirtualNode(85),
                    VirtualNodeBuilder::createVirtualNode(86),
                    VirtualNodeBuilder::createVirtualNode(87),
                    VirtualNodeBuilder::createVirtualNode(88),
                    VirtualNodeBuilder::createVirtualNode(180),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(75),
                )
                ->addActiveNode(
                    self::NODE_3,
                    VirtualNodeBuilder::createVirtualNode(90),
                    VirtualNodeBuilder::createVirtualNode(185),
                )
                ->getRing(),
            [
                '1-74' => [[self::NODE_1, self::NODE_2, self::NODE_3], []],
                '75-84' => [[self::NODE_2, self::NODE_1, self::NODE_3], []],
                '85-89' => [[self::NODE_1, self::NODE_3, self::NODE_2], []],
                '90-179' => [[self::NODE_3, self::NODE_1, self::NODE_2], []],
                '180-184' => [[self::NODE_1, self::NODE_3, self::NODE_2], []],
                '185-0' => [[self::NODE_3, self::NODE_1, self::NODE_2], []],
            ],
        ];
    }

    public static function getSpecialTestCases(): \Generator
    {
        yield 'Ring with disabled virtual nodes' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(75),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(75, false),
                    VirtualNodeBuilder::createVirtualNode(150, false),
                    VirtualNodeBuilder::createVirtualNode(180, false),
                )
                ->addActiveNode(
                    self::NODE_3,
                    VirtualNodeBuilder::createVirtualNode(75, false),
                    VirtualNodeBuilder::createVirtualNode(150, false),
                    VirtualNodeBuilder::createVirtualNode(200, false),
                    VirtualNodeBuilder::createVirtualNode(80, false),
                )
                ->getRing(),
            [
                '0-359' => [[self::NODE_1], []],
            ],
        ];
        yield 'Ring with disabled virtual nodes 2' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(75),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(75, false),
                    VirtualNodeBuilder::createVirtualNode(76, false),
                    VirtualNodeBuilder::createVirtualNode(150, false),
                    VirtualNodeBuilder::createVirtualNode(180, false),
                    VirtualNodeBuilder::createVirtualNode(201, false),
                )
                ->addActiveNode(
                    self::NODE_3,
                    VirtualNodeBuilder::createVirtualNode(75, false),
                    VirtualNodeBuilder::createVirtualNode(150, false),
                    VirtualNodeBuilder::createVirtualNode(200),
                    VirtualNodeBuilder::createVirtualNode(80, false),
                )
                ->getRing(),
            [
                '1-199' => [[self::NODE_1, self::NODE_3], []],
                '200-0' => [[self::NODE_3, self::NODE_1], []],
            ],
        ];
        yield 'Node state does not matters' => [
            self::N_2,
            RingBuilder::createRing()
                ->addNode(
                    self::NODE_1,
                    NodeState::JOINING,
                    VirtualNodeBuilder::createVirtualNode(1),
                )
                ->addNode(
                    self::NODE_2,
                    NodeState::ERROR,
                    VirtualNodeBuilder::createVirtualNode(150),
                )
                ->addNode(
                    self::NODE_3,
                    NodeState::RECOVERING,
                    VirtualNodeBuilder::createVirtualNode(180),
                )
                ->addNode(
                    self::NODE_4,
                    NodeState::LEAVING,
                    VirtualNodeBuilder::createVirtualNode(185),
                )
                ->addNode(
                    self::NODE_5,
                    NodeState::JOINING_ERROR, // should not be possible
                    VirtualNodeBuilder::createVirtualNode(190),
                )
                ->getRing(),
            [
                '1-149' => [[self::NODE_1, self::NODE_2], [self::NODE_3, self::NODE_4, self::NODE_5]],
                '150-179' => [[self::NODE_2, self::NODE_3], [self::NODE_4, self::NODE_5, self::NODE_1]],
                '180-184' => [[self::NODE_3, self::NODE_4], [self::NODE_5, self::NODE_1, self::NODE_2]],
                '185-189' => [[self::NODE_4, self::NODE_5], [self::NODE_1, self::NODE_2, self::NODE_3]],
                '190-0' => [[self::NODE_5, self::NODE_1], [self::NODE_2, self::NODE_3, self::NODE_4]],
            ],
        ];
    }

    public static function getErrorTestCases(): \Generator
    {
        yield 'Empty ring' => [
            self::N_3,
            RingBuilder::createRing()
                ->getRing(),
        ];
        yield 'No virtual node active' => [
            self::N_3,
            RingBuilder::createRing()
                ->addActiveNode(
                    self::NODE_1,
                    VirtualNodeBuilder::createVirtualNode(75, false),
                )
                ->addActiveNode(
                    self::NODE_2,
                    VirtualNodeBuilder::createVirtualNode(180, false),
                )
                ->getRing(),
        ];
    }

    /**
     * @dataProvider getSimpleTestCases
     * @dataProvider getReplicationOnlyOnDistinctPhysicalNodeRuleTestCases
     * @dataProvider getSpecialTestCases
     *
     * with index 0 containing all expected coordinator nodes and index 1 all others nodes
     *
     * @param array<string, array{"0": array<string>, "1": array<string>}> $expectedPreferenceList
     */
    public function testSuccessful(
        int $N,
        Ring $ring,
        array $expectedPreferenceList,
    ): void {
    }

    /**
     * @dataProvider getErrorTestCases
     */
    public function testError(
        int $N,
        Ring $ring,
        array $expectedPreferenceList,
    ): void {
    }
}
