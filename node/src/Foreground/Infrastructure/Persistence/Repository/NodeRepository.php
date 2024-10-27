<?php

namespace App\Foreground\Infrastructure\Persistence\Repository;

use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Out\Node\FinderInterface;
use App\Foreground\Infrastructure\Persistence\Mapper\NodeMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NodeEntity>
 */
class NodeRepository extends ServiceEntityRepository implements FinderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeEntity::class);
    }

    public function getLocalEntry(): Node
    {
        /** @var NodeEntity $selfNode */
        $selfNode = $this->findOneBy(['selfEntry' => true]);

        return NodeMapper::entityToDto($selfNode);
    }

    public function findByIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('n')
            ->where('n.id IN (:ids)')
            ->setParameter('ids', $ids);

        /** @var array<NodeEntity> $dbRows */
        $dbRows = $qb->getQuery()->execute();

        return array_reduce($dbRows, function (array $carry, NodeEntity $node) {
            $dto = NodeMapper::entityToDto($node);
            $carry[$dto->getStringId()] = $dto;

            return $carry;
        }, []);
    }
}
