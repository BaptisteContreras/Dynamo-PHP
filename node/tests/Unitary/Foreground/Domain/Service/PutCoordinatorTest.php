<?php

namespace App\Tests\Unitary\Foreground\Domain\Service;

use App\Foreground\Domain\Model\Aggregate\Node\Collection\VirtualNodeCollection;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceEntry;
use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Model\Aggregate\Put\Metadata;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;
use App\Foreground\Domain\Service\Coordinator;
use App\Foreground\Domain\Service\Forward\Forwarder;
use App\Foreground\Domain\Service\Local\LocalCoordinator;
use App\Shared\Domain\Const\MembershipState;
use App\Shared\Domain\Const\NodeState;
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

    private Forwarder $forwarder;

    private LocalCoordinator $localCoordinator;

    protected function setUp(): void
    {
        $this->preferenceListFinder = $this->createMock(PreferenceListFinder::class);
        $this->nodeFinder = $this->createMock(NodeFinder::class);
        $this->forwarder = $this->createMock(Forwarder::class);
        $this->localCoordinator = $this->createMock(LocalCoordinator::class);

        $this->coordinator = new Coordinator(
            $this->preferenceListFinder,
            $this->nodeFinder,
            $this->localCoordinator,
            $this->forwarder
        );
    }

    public function getForwardCases(): \Generator
    {
        yield 'Forward success and local node has left the ring' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            $this->createNode(self::NODE_2, MembershipState::LEFT, selfEntry: true),
            self::NODE_4,
        ];
        yield 'Forward success and local node is OK' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            $this->createNode(self::NODE_2, selfEntry: true),
            self::NODE_4,
        ];
        yield 'Forward success and local node is in error state' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            $this->createNode(self::NODE_2, nodeState: NodeState::ERROR, selfEntry: true),
            self::NODE_4,
        ];
    }

    public function getLocalCases(): \Generator
    {
        yield 'Forward success and local node has left the ring' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            $this->createNode(self::NODE_1, MembershipState::LEFT, selfEntry: true),
            self::NODE_1,
        ];
        yield 'Forward success and local node is OK' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            $this->createNode(self::NODE_1, selfEntry: true),
            self::NODE_1,
        ];
        yield 'Forward success and local node is in error state' => [
            new Item('key1', new Metadata('v1', self::ITEM_RING_KEY), 'data'),
            new PreferenceEntry(self::ITEM_RING_KEY, UuidV7::fromString(self::NODE_1), [UuidV7::fromString(self::NODE_2)], [UuidV7::fromString(self::NODE_3), UuidV7::fromString(self::NODE_4)], new UuidV7()),
            $this->createNode(self::NODE_1, nodeState: NodeState::ERROR, selfEntry: true),
            self::NODE_1,
        ];
    }

    /**
     * @dataProvider getForwardCases
     */
    public function testForwardSuccess(Item $item, PreferenceEntry $preferenceEntry, Node $localNode, string $expectedSlotCoordinatorId): void
    {
        $preferenceList = new PreferenceList([$preferenceEntry]);

        $this->preferenceListFinder->expects(self::once())->method('getPreferenceList')->willReturn($preferenceList);
        $this->nodeFinder->expects(self::once())->method('getLocalEntry')->willReturn($localNode);

        $this->forwarder->expects(self::once())->method('forwardWrite')->with($item)->willReturn($this->createNode($expectedSlotCoordinatorId));
        $this->localCoordinator->expects(self::never())->method('handleWriteLocally');

        $slotCoordinator = $this->coordinator->handleWrite($item);

        self::assertEquals($expectedSlotCoordinatorId, $slotCoordinator->getStringId());
    }

    /**
     * @dataProvider getLocalCases
     */
    public function testLocalForwardSuccess(Item $item, PreferenceEntry $preferenceEntry, Node $localNode, string $expectedSlotCoordinatorId): void
    {
        $preferenceList = new PreferenceList([$preferenceEntry]);

        $this->preferenceListFinder->expects(self::once())->method('getPreferenceList')->willReturn($preferenceList);
        $this->nodeFinder->expects(self::once())->method('getLocalEntry')->willReturn($localNode);

        $this->forwarder->expects(self::never())->method('forwardWrite');
        $this->localCoordinator->expects(self::once())->method('handleWriteLocally')->with($item)->willReturn($this->createNode($expectedSlotCoordinatorId));

        $slotCoordinator = $this->coordinator->handleWrite($item);

        self::assertEquals($expectedSlotCoordinatorId, $slotCoordinator->getStringId());
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
