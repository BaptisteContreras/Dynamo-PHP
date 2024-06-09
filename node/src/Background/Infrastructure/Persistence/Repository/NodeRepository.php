<?php

namespace App\Background\Infrastructure\Persistence\Repository;

use App\Background\Domain\Model\Aggregate\Ring\Collection\NodeCollection;
use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\Ring;
use App\Background\Domain\Out\Ring\FinderInterface;
use App\Background\Domain\Out\Ring\UpdaterInterface;
use App\Background\Infrastructure\Persistence\Mapper\NodeMapper;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NodeEntity>
 */
class NodeRepository extends ServiceEntityRepository implements FinderInterface, UpdaterInterface
{
    public function __construct(ManagerRegistry $registry, private readonly VirtualNodeRepository $virtualNodeRepository)
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
        $em = $this->getEntityManager();

        foreach ($ring->getNodes() as $node) {
            $nodeEntity = $this->createOrUpdate($node);
            $nodeEntity->setVirtualNodes($this->convertVirtualNodeCollectionForDoctrine($node, $nodeEntity));

            $em->persist($nodeEntity);
        }

        $em->flush();
    }

    private function createOrUpdate(Node $node): NodeEntity
    {
        $nodeEntity = $this->find($node->getId());

        if ($nodeEntity instanceof NodeEntity) {
            NodeMapper::mergeDtoInEntity($node, $nodeEntity);
        } else {
            $nodeEntity = NodeMapper::dtoToEntity($node);
        }

        return $nodeEntity;
    }

    /**
     * @return Collection<int, VirtualNodeEntity>
     */
    private function convertVirtualNodeCollectionForDoctrine(Node $node, NodeEntity $nodeEntity): Collection
    {
        /** @var ArrayCollection<int, VirtualNodeEntity> $virtualNodeEntities */
        $virtualNodeEntities = new ArrayCollection();

        foreach ($node->getVirtualNodes() as $virtualNode) {
            $virtualNodeEntities->add($this->virtualNodeRepository->createOrUpdate(
                $virtualNode,
                $nodeEntity
            ));
        }

        return $virtualNodeEntities;
    }
}
