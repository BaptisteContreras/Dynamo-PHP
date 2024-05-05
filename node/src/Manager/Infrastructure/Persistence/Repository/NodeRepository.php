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

    public function createSelfNode(string $networkAddress, int $networkPort, int $weight, bool $isSeed, string $label, \DateTimeImmutable $joinedAt): Node
    {
        $selfNode = new NodeEntity(
            $networkAddress,
            $networkPort,
            NodeState::JOINING,
            $joinedAt,
            $weight,
            true,
            $isSeed,
            $label
        );

        $this->getEntityManager()->persist($selfNode);
        $this->getEntityManager()->flush();

        return $this->entityToDto($selfNode);
    }

    public function saveNode(Node $node): void
    {
        $this->getEntityManager()->persist($this->dtoToEntity($node));
    }

    public function findAll(): array
    {
        /** @var array<NodeEntity> $entityArray */
        $entityArray = parent::findAll();

        return array_map(fn (NodeEntity $nodeEntity) => $this->entityToDto($nodeEntity), $entityArray);
    }

    public function findSelfEntry(): ?Node
    {
        /** @var ?NodeEntity $selfNode */
        $selfNode = $this->findOneBy(['selfEntry' => true]);

        return $selfNode ? $this->entityToDto($selfNode) : null;
    }

    private function dtoToEntity(Node $node): NodeEntity
    {
        return new NodeEntity(
            $node->getHost(),
            $node->getNetworkPort(),
            $node->getState(),
            $node->getJoinedAt(),
            $node->getWeight(),
            $node->isSelfEntry(),
            $node->isSeed(),
            $node->getLabel(),
            $node->getId()
        );
    }

    private function entityToDto(NodeEntity $nodeEntity): Node
    {
        /** @var UuidV7 $id */
        $id = $nodeEntity->getId();

        return new Node(
            $id,
            $nodeEntity->getHost(),
            $nodeEntity->getNetworkPort(),
            $nodeEntity->getState(),
            $nodeEntity->getJoinedAt(),
            $nodeEntity->getWeight(),
            $nodeEntity->isSelfEntry(),
            $nodeEntity->isSeed(),
            $nodeEntity->getLabel()
        );
    }
}
