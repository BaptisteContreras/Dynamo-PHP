<?php

namespace DataFixtures\Manager;

use App\Shared\Domain\Const\NodeState;
use App\Shared\Infrastructure\Persistence\Doctrine\Node;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

class NodeFixtures extends Fixture
{
    final public const string NODE_1 = 'node_1';
    final public const string NODE_2 = 'node_2';

    public function load(ObjectManager $manager): void
    {
        $node1 = new Node(
            '127.0.0.1',
            8000,
            NodeState::JOINING,
            new \DateTimeImmutable('2024-09-09 14:46:00'),
            5,
            true,
            false,
            'D',
            new \DateTimeImmutable('2024-09-09 14:50:00'),
            new ArrayCollection(),
            UuidV7::fromString('0191d6db-ada6-7632-b6d4-56f5094b0a86')
        );

        $this->setReference(self::NODE_1, $node1);

        $node2 = new Node(
            'localhost',
            9999,
            NodeState::UP,
            new \DateTimeImmutable('2024-09-10 14:46:00'),
            2,
            false,
            true,
            'E',
            new \DateTimeImmutable('2024-09-10 14:50:00'),
            new ArrayCollection(),
            UuidV7::fromString('0191d6db-bbd5-77d2-a8e2-93668b695553')
        );

        $this->setReference(self::NODE_1, $node1);
        $this->setReference(self::NODE_2, $node2);

        $manager->persist($node1);
        $manager->persist($node2);
        $manager->flush();
    }
}
