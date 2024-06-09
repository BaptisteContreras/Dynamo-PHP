<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use App\Background\Infrastructure\Persistence\Mapper\VirtualNodeMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VirtualNodeEntity>
 */
class VirtualNodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualNodeEntity::class);
    }

    public function createOrUpdate(VirtualNode $virtualNode, NodeEntity $nodeEntity): VirtualNodeEntity
    {
        $virtualNodeEntity = $this->find($virtualNode->getId());

        if ($virtualNodeEntity instanceof VirtualNodeEntity) {
            VirtualNodeMapper::mergeDtoInEntity($virtualNode, $virtualNodeEntity);
        } else {
            $virtualNodeEntity = VirtualNodeMapper::dtoToEntity($virtualNode, $nodeEntity);
        }

        $this->getEntityManager()->persist($virtualNodeEntity);

        return $virtualNodeEntity;
    }
}
