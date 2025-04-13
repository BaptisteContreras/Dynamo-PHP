<?php

namespace App\Tests\Unitary\Foreground\Domain\Service;

use App\Foreground\Domain\Exception\CannotForwardWriteException;
use App\Foreground\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceEntry;
use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Model\Aggregate\Put\Metadata;
use App\Foreground\Domain\Model\Const\ForwardResult;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;
use App\Foreground\Domain\Service\Coordinator;
use App\Foreground\Domain\Service\Forward\ForwardStrategyInterface;
use App\Shared\Domain\Const\MembershipState;
use App\Shared\Domain\Const\NodeState;
use App\Shared\Domain\Event\EventBusInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

class PutCoordinatorTest extends TestCase
{
    private const ITEM_RING_KEY = 0;

    private const NODE_1 = '01901352-03dc-7cdf-8bae-46478df51aeb';
    private const NODE_2 = '01901352-03dc-7cdf-8bae-46478df51aec';
    private const NODE_3 = '01909912-05b9-76c9-953a-0b0bbb5d858d';
    private const NODE_4 = '01909912-e139-72da-98d5-c39667a6f652';
    private const NODE_5 = '01909912-f8b0-7c9b-bced-c3bb64b00e0f';
    private const NODE_6 = '01909e0f-3899-779f-8093-0473397e8ea0';
    private const NODE_7 = '01909e1d-ffa3-7405-ae38-20bc09a31247';
    private const NODE_8 = '01909e1e-7c18-76e5-b50a-ccc873b9ccef';
    private Coordinator $coordinator;

    private PreferenceListFinder $preferenceListFinder;

    private NodeFinder $nodeFinder;

    private ForwardStrategyInterface $forwardStrategy;

    private EventBusInterface $eventBus;

    protected function setUp(): void
    {
        $this->preferenceListFinder = $this->createMock(PreferenceListFinder::class);
        $this->nodeFinder = $this->createMock(NodeFinder::class);
        $this->forwardStrategy = $this->createMock(ForwardStrategyInterface::class);
        $this->eventBus = $this->createMock(EventBusInterface::class);

        $this->coordinator = new Coordinator(
            $this->preferenceListFinder,
            $this->nodeFinder,
            $this->forwardStrategy,
            $this->eventBus
        );
    }

    public function getSuccessCases(): \Generator
    {
        yield 'Success but skip first 3 nodes because of edge cases' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            [
                // first edge case : NODE_1 is unknown (i.e is not in the node table of current node)
                self::NODE_2 => $this->createNode(self::NODE_2, MembershipState::LEFT), // second edge case : NODE_2 has left the ring (i.e cannot participate to ring operation)
                self::NODE_3 => $this->createNode(self::NODE_3, nodeState: NodeState::ERROR), // third edge case : NODE_3 is locally in an error state (i.e cannot participate to ring operation)
                self::NODE_4 => $this->createNode(self::NODE_4), // is OK
            ],
            self::NODE_4,
            [self::NODE_4 => ForwardResult::SUCCESS],
        ];
        yield 'Success but skip first 2 nodes because of forward failure' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2), UuidV7::fromString(self::NODE_3)], [], new UuidV7()),
            [
                self::NODE_1 => $this->createNode(self::NODE_1), // will trigger ForwardResult::TIMEOUT
                self::NODE_2 => $this->createNode(self::NODE_2), // will trigger ForwardResult::TECHNICAL_ERROR
                self::NODE_3 => $this->createNode(self::NODE_3), // is OK
            ],
            self::NODE_3,
            [
                self::NODE_1 => ForwardResult::TIMEOUT,
                self::NODE_2 => ForwardResult::TECHNICAL_ERROR,
                self::NODE_3 => ForwardResult::SUCCESS,
            ],
        ];
        yield 'Success but skip first 3 nodes because of edge (variant with self entry)' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            [
                // first edge case : NODE_1 is unknown (i.e is not in the node table of current node)
                self::NODE_2 => $this->createNode(self::NODE_2, MembershipState::LEFT, selfEntry: true), // second edge case : NODE_2 has left the ring (i.e cannot participate to ring operation)
                self::NODE_3 => $this->createNode(self::NODE_3, nodeState: NodeState::ERROR, selfEntry: true), // third edge case : NODE_3 is locally in an error state (i.e cannot participate to ring operation)
                self::NODE_4 => $this->createNode(self::NODE_4), // is OK
            ],
            self::NODE_4,
            [self::NODE_4 => ForwardResult::SUCCESS],
        ];
        //        yield 'Success but skip first 3 nodes because of edge cases and write is handled by "self node"' => [
        //            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
        //            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
        //            [
        //                // first edge case : NODE_1 is unknown (i.e is not in the node table of current node)
        //                self::NODE_2 => $this->createNode(self::NODE_2, MembershipState::LEFT), // second edge case : NODE_2 has left the ring (i.e cannot participate to ring operation)
        //                self::NODE_3 => $this->createNode(self::NODE_3, nodeState: NodeState::ERROR), // third edge case : NODE_3 is locally in an error state (i.e cannot participate to ring operation)
        //                self::NODE_4 => $this->createNode(self::NODE_4, selfEntry: true), // is OK
        //            ],
        //            self::NODE_4,
        //            [self::NODE_4 => ForwardResult::SUCCESS]
        //        ];
        //        yield 'Success but skip first 2 nodes because of forward failure and write is handled by "self node"' => [
        //            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
        //            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2), UuidV7::fromString(self::NODE_3)], [], new UuidV7()),
        //            [
        //                self::NODE_1 => $this->createNode(self::NODE_1), // will trigger ForwardResult::TIMEOUT
        //                self::NODE_2 => $this->createNode(self::NODE_2), // will trigger ForwardResult::TECHNICAL_ERROR
        //                self::NODE_3 => $this->createNode(self::NODE_3, selfEntry: true), // is OK
        //            ],
        //            self::NODE_3,
        //            [
        //                self::NODE_1 => ForwardResult::TIMEOUT,
        //                self::NODE_2 => ForwardResult::TECHNICAL_ERROR,
        //                self::NODE_3 => ForwardResult::SUCCESS,
        //            ]
        //        ];
    }

    public function getFailureCases(): \Generator
    {
        yield 'Failure and skip first 3 nodes because of edge cases' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            [
                // first edge case : NODE_1 is unknown (i.e is not in the node table of current node)
                self::NODE_2 => $this->createNode(self::NODE_2, MembershipState::LEFT), // second edge case : NODE_2 has left the ring (i.e cannot participate to ring operation)
                self::NODE_3 => $this->createNode(self::NODE_3, nodeState: NodeState::ERROR), // third edge case : NODE_3 is locally in an error state (i.e cannot participate to ring operation)
            ],
            [],
        ];
        yield 'Failure and skip first 3 nodes because of forward failure' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2), UuidV7::fromString(self::NODE_3)], [], new UuidV7()),
            [
                self::NODE_1 => $this->createNode(self::NODE_1), // will trigger ForwardResult::TIMEOUT
                self::NODE_2 => $this->createNode(self::NODE_2), // will trigger ForwardResult::TECHNICAL_ERROR
                self::NODE_3 => $this->createNode(self::NODE_3), // will trigger ForwardResult::TIMEOUT
            ],
            [
                self::NODE_1 => ForwardResult::TIMEOUT,
                self::NODE_2 => ForwardResult::TECHNICAL_ERROR,
                self::NODE_3 => ForwardResult::TIMEOUT,
            ],
        ];
    }

    /**
     * @dataProvider getSuccessCases
     *
     * @param array<string, Node>          $nodes
     * @param array<string, ForwardResult> $forwardCalls
     */
    public function testForwardSuccess(Item $item, PreferenceEntry $preferenceEntry, array $nodes, string $expectedSlotCoordinatorId, array $forwardCalls): void
    {
        $preferenceList = new PreferenceList([$preferenceEntry]);

        $failedForwardCalls = array_filter($forwardCalls, fn (ForwardResult $result) => ForwardResult::SUCCESS !== $result);

        $this->preferenceListFinder->expects(self::once())->method('getPreferenceList')->willReturn($preferenceList);
        $this->nodeFinder->expects(self::once())->method('findByIds')->with($preferenceEntry->getCoordinatorsPriorityList())->willReturn($nodes);
        $this->eventBus->expects(self::exactly(count($failedForwardCalls)))->method('publish');

        $this->forwardStrategy
            ->expects($this->exactly(count($forwardCalls)))
            ->method('forwardItemWriteTo')
            ->willReturnCallback(function (Node $nodeUsed, Item $itemUsed) use ($forwardCalls, $item) {
                static $i = 0;
                $this->assertEquals($item, $itemUsed);
                $currentCall = array_keys($forwardCalls)[$i++];
                $this->assertEquals($currentCall, $nodeUsed->getStringId());

                return $forwardCalls[$currentCall];
            });

        $slotCoordinator = $this->coordinator->forwardWrite($item);

        self::assertEquals($expectedSlotCoordinatorId, $slotCoordinator->getStringId());
    }

    /**
     * @dataProvider getFailureCases
     *
     * @param array<string, Node>          $nodes
     * @param array<string, ForwardResult> $forwardCalls
     */
    public function testForwardFailure(Item $item, PreferenceEntry $preferenceEntry, array $nodes, array $forwardCalls): void
    {
        $preferenceList = new PreferenceList([$preferenceEntry]);

        $failedForwardCalls = array_filter($forwardCalls, fn (ForwardResult $result) => ForwardResult::SUCCESS !== $result);

        $this->preferenceListFinder->expects(self::once())->method('getPreferenceList')->willReturn($preferenceList);
        $this->nodeFinder->expects(self::once())->method('findByIds')->with($preferenceEntry->getCoordinatorsPriorityList())->willReturn($nodes);
        $this->eventBus->expects(self::exactly(count($failedForwardCalls)))->method('publish');

        $this->forwardStrategy
            ->expects($this->exactly(count($forwardCalls)))
            ->method('forwardItemWriteTo')
            ->willReturnCallback(function (Node $nodeUsed, Item $itemUsed) use ($forwardCalls, $item) {
                static $i = 0;
                $this->assertEquals($item, $itemUsed);
                $currentCall = array_keys($forwardCalls)[$i++];
                $this->assertEquals($currentCall, $nodeUsed->getStringId());

                return $forwardCalls[$currentCall];
            });

        $this->expectException(CannotForwardWriteException::class);

        $this->coordinator->forwardWrite($item);
    }

    private function createNode(string $nodeId, MembershipState $membershipState = MembershipState::JOINED, NodeState $nodeState = NodeState::UP, bool $selfEntry = false): Node
    {
        $virtualNodesCollection = VirtualNodeCollection::createEmpty();

        return new Node(
            UuidV7::fromString($nodeId),
            '127.0.0.1',
            (string) rand(80, 65000),
            $membershipState,
            $selfEntry,
            sprintf('LABEL_%s', rand()),
            $nodeState,
            $virtualNodesCollection,
        );
    }
}
