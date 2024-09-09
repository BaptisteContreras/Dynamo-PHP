<?php

namespace DataFixtures\Manager;

use App\Shared\Infrastructure\Persistence\Doctrine\Node;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

class VirtualNodeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var Node $node1 */
        $node1 = $this->getReference(NodeFixtures::NODE_1);
        $vm1 = new VirtualNode(
            sprintf('%s_1', $node1->getLabel()),
            99,
            $node1,
            new \DateTimeImmutable('2024-09-09 14:55:0'),
            true,
            UuidV7::fromString('0191d6db-733f-7d51-839c-954f6243f4c0')
        );

        /** @var Node $node2 */
        $node2 = $this->getReference(NodeFixtures::NODE_2);
        $vm2 = new VirtualNode(
            sprintf('%s_1', $node2->getLabel()),
            180,
            $node2,
            new \DateTimeImmutable('2024-09-10 14:55:0'),
            true,
            UuidV7::fromString('0191d6db-8fea-7415-bdf4-228c7184d221')
        );

        $manager->persist($vm1);
        $manager->persist($vm2);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NodeFixtures::class,
        ];
    }
}
