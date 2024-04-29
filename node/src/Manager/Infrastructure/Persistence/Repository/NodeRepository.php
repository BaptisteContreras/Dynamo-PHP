<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Model\Node;
use App\Manager\Domain\Out\Node\CreatorInterface;
use App\Manager\Domain\Out\Node\FinderInterface;
use App\Shared\Domain\Const\NodeState;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV7;

class NodeRepository extends ServiceEntityRepository implements FinderInterface, CreatorInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeEntity::class);
    }

    public function createSelfNode(string $networkAddress, int $networkPort, int $weight, \DateTimeImmutable $joinedAt): void
    {
        $selfNode = new NodeEntity(
            $networkAddress,
            $networkPort,
            NodeState::JOINING,
            $joinedAt,
            $weight,
            true
        );

        $this->getEntityManager()->persist($selfNode);
    }

    public function saveNode(Node $node): void
    {
        $this->getEntityManager()->persist($this->dtoToEntity($node));
    }

    private function dtoToEntity(Node $node): NodeEntity
    {
        return new NodeEntity(
            $node->getNetworkAddress(),
            $node->getNetworkPort(),
            $node->getState(),
            $node->getJoinedAt(),
            $node->getWeight(),
            $node->isSelfEntry(),
            $node->getId(),
        );
    }

    public function findAll(): array
    {
        /** @var array<NodeEntity> $entityArray */
        $entityArray = parent::findAll();

        return array_map(fn (NodeEntity $nodeEntity) => $this->entityToDto($nodeEntity), $entityArray);
    }

    private function entityToDto(NodeEntity $nodeEntity): Node
    {
        /** @var UuidV7 $id */
        $id = $nodeEntity->getId();

        return new Node(
            $id,
            $nodeEntity->getNetworkAddress(),
            $nodeEntity->getNetworkPort(),
            $nodeEntity->getState(),
            $nodeEntity->getJoinedAt(),
            $nodeEntity->getWeight(),
            $nodeEntity->isSelfEntry()
        );
    }

    public function findSelfEntry(): ?Node
    {
        /** @var ?NodeEntity $selfNode */
        $selfNode = $this->findOneBy(['selfEntry' => true]);

        return $selfNode ? $this->entityToDto($selfNode) : null;
    }
}
