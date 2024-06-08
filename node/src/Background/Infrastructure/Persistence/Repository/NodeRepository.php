<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Out\Ring\FinderInterface;
use App\Background\Domain\Out\Ring\UpdaterInterface;
use App\Background\Infrastructure\Persistence\Mapper\NodeMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NodeEntity>
 */
class NodeRepository extends ServiceEntityRepository implements FinderInterface, UpdaterInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeEntity::class);
    }

    public function getLocalRing(): Ring
    {
        return new Ring(
            new NodeCollection(
                array_map(fn (NodeEntity $nodeEntity) => NodeMapper::entityToDto($nodeEntity), $this->findAll())
            )
        );
    }

    public function saveRing(Ring $ring): void
    {
        // TODO: Implement saveRing() method.
    }
}
