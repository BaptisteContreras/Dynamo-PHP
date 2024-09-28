<?php

namespace DataFixtures\Background;

use App\Shared\Domain\Const\HistoryEventType;
use App\Shared\Domain\Const\MembershipState;
use App\Shared\Domain\Const\NodeState;
use App\Shared\Infrastructure\Persistence\Doctrine\HistoryEvent;
use App\Shared\Infrastructure\Persistence\Doctrine\Node;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

class SyncMembershipFixtures extends Fixture
{
    public const string NODE_1 = '0191d6db-ada6-7632-b6d4-56f5094b0a86';
    public const string VIRUTAL_NODE_1 = '0191d6db-733f-7d51-839c-954f6243f4c0';

    public function load(ObjectManager $manager): void
    {
        $node1VirtualNodesCollection = new ArrayCollection();
        $node1 = new Node(
            '127.0.0.1',
            8000,
            MembershipState::JOINED,
            new \DateTimeImmutable('2024-09-09 14:46:00'),
            5,
            true,
            false,
            'D',
            NodeState::UP,
            new \DateTimeImmutable('2024-09-09 14:50:00'),
            $node1VirtualNodesCollection,
            UuidV7::fromString(self::NODE_1)
        );

        $vm1 = new VirtualNode(
            sprintf('%s_1', $node1->getLabel()),
            0,
            $node1,
            new \DateTimeImmutable('2024-09-09 14:55:0'),
            true,
            UuidV7::fromString(self::VIRUTAL_NODE_1)
        );

        $node1VirtualNodesCollection->add($vm1);

        $manager->persist($vm1);
        $manager->persist($node1);

        $this->loadHistory($manager);

        $manager->flush();
    }

    private function loadHistory(ObjectManager $manager)
    {
        $h = new HistoryEvent(
            UuidV7::fromString('019239be-19ea-7ba7-b38d-1855553df0f7'),
            UuidV7::fromString(self::NODE_1),
            HistoryEventType::CHANGE_MEMBERSHIP,
            new \DateTimeImmutable('2024-09-09 16:50:00'),
            (string) MembershipState::JOINED->value,
            null,
            null
        );

        $manager->persist($h);
    }
}
