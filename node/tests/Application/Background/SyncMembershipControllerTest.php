<?php

namespace App\Tests\Application\Background;

use App\Shared\Domain\Const\RingInformations;
use App\Shared\Infrastructure\Persistence\Doctrine\HistoryEvent;
use App\Shared\Infrastructure\Persistence\Doctrine\Node;
use App\Shared\Infrastructure\Persistence\Doctrine\PreferenceListEntry;
use App\Tests\Application\AppTestCase;
use DataFixtures\Background\SyncMembershipFixtures;
use Doctrine\ORM\EntityManagerInterface;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;

class SyncMembershipControllerTest extends AppTestCase
{
    private const string URI = 'background/sync/membership/v1';

    private const HISTORY_EVENT_1 = '0191d74d-a192-713f-982d-80777d8c5589';
    private const NODE_2 = '0191d74b-c96e-70eb-963e-33c06382c6cd';
    private const VIRTUAL_NODE_2 = '0191d74b-7782-753d-aa01-57b7223a5492';
    private const JSON_DATA = [
        'sourceNode' => self::NODE_2,
        'historyEvents' => [
            [
                'id' => self::HISTORY_EVENT_1,
                'node' => self::NODE_2,
                'type' => 0,
                'data' => '0',
                'eventTime' => '2024-10-09T14:56:49.086Z',
            ],
        ],
        'nodes' => [
            [
                'id' => self::NODE_2,
                'host' => 'localhost',
                'networkPort' => 9003,
                'state' => 0,
                'joinedAt' => '2024-09-09T14:56:49.086Z',
                'weight' => 1,
                'seed' => true,
                'updatedAt' => '2024-09-09T14:56:49.086Z',
                'label' => 'F',
                'virtualNodes' => [
                    [
                        'id' => self::VIRTUAL_NODE_2,
                        'label' => 'F1',
                        'slot' => 180,
                        'createdAt' => '2024-09-09T14:56:49.086Z',
                        'active' => true,
                    ],
                ],
            ],
        ],
    ];

    public function testMembershipSyncSuccess(): void
    {
        $this->databaseTool->loadFixtures([
            SyncMembershipFixtures::class,
        ]);

        $this->client->followRedirects();
        $this->postJson(self::URI, self::JSON_DATA);

        self::assertResponseIsSuccessful();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $nodes = $em->getRepository(Node::class)->findAll();
        $historyEvents = $em->getRepository(HistoryEvent::class)->findAll();
        $preferenceListEntries = $em->getRepository(PreferenceListEntry::class)->findAll();

        self::assertCount(2, $nodes);

        $nodesMap = [];
        foreach ($nodes as $node) {
            $nodesMap[$node->getId()->toRfc4122()] = $node;
        }

        $node1Result = $nodesMap[SyncMembershipFixtures::NODE_1];
        $node2Result = $nodesMap[self::NODE_2];

        self::assertCount(1, $node1Result->getVirtualNodes());
        self::assertCount(1, $node2Result->getVirtualNodes());

        $virtualNode1 = $node1Result->getVirtualNodes()->first();
        $virtualNode2 = $node2Result->getVirtualNodes()->first();

        self::assertEquals(SyncMembershipFixtures::VIRUTAL_NODE_1, $virtualNode1->getId()->toRfc4122());
        self::assertEquals(self::VIRTUAL_NODE_2, $virtualNode2->getId()->toRfc4122());

        self::assertTrue($virtualNode1->isActive());
        self::assertTrue($virtualNode2->isActive());

        self::assertCount(2, $historyEvents);

        $newHistoryEventFiltered = array_filter($historyEvents, fn (HistoryEvent $historyEvent) => self::HISTORY_EVENT_1 === $historyEvent->getId()->toRfc4122());
        self::assertCount(1, $newHistoryEventFiltered);

        /** @var HistoryEvent $newHistoryEvent */
        $newHistoryEvent = current($newHistoryEventFiltered);

        assertEquals(self::NODE_2, $newHistoryEvent->getNode()->toRfc4122());
        self::assertNotNull($newHistoryEvent->getReceivedAt());

        self::assertCount(RingInformations::RING_SIZE, $preferenceListEntries);

        foreach ($preferenceListEntries as $preferenceEntry) {
            if ($preferenceEntry->getSlot() >= 0 && $preferenceEntry->getSlot() < 180) {
                assertEquals(SyncMembershipFixtures::NODE_1, $preferenceEntry->getOwnerId()->toRfc4122());
                assertEquals(self::NODE_2, current($preferenceEntry->getCoordinatorsIds())->toRfc4122());
            } else {
                assertEquals(self::NODE_2, $preferenceEntry->getOwnerId()->toRfc4122());
                assertEquals(SyncMembershipFixtures::NODE_1, current($preferenceEntry->getCoordinatorsIds())->toRfc4122());
            }
            assertCount(1, $preferenceEntry->getCoordinatorsIds());
            assertEmpty($preferenceEntry->getOthersNodesIds());
        }
    }
}
