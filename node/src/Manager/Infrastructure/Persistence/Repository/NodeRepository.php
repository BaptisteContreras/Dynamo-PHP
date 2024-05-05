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
        $nodeEntity = $this->find($node->getId());

        if ($nodeEntity instanceof NodeEntity) {
            $this->mergeDtoInEntity($node, $nodeEntity);
        } else {
            $nodeEntity = $this->dtoToEntity($node);
        }

        $this->getEntityManager()->persist($nodeEntity);
        $this->getEntityManager()->flush();
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

    private function dtoToEntity(Node $dto): NodeEntity
    {
        return new NodeEntity(
            $dto->getHost(),
            $dto->getNetworkPort(),
            $dto->getState(),
            $dto->getJoinedAt(),
            $dto->getWeight(),
            $dto->isSelfEntry(),
            $dto->isSeed(),
            $dto->getLabel(),
            $dto->getId()
        );
    }

    private function entityToDto(NodeEntity $entity): Node
    {
        /** @var UuidV7 $id */
        $id = $entity->getId();

        return new Node(
            $id,
            $entity->getHost(),
            $entity->getNetworkPort(),
            $entity->getState(),
            $entity->getJoinedAt(),
            $entity->getWeight(),
            $entity->isSelfEntry(),
            $entity->isSeed(),
            $entity->getLabel()
        );
    }

    private function mergeDtoInEntity(Node $dto, NodeEntity $entity): void
    {
        $entity
            ->setState($dto->getState())
        ;
    }
}
