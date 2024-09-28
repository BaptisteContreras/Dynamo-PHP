<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Model\Aggregate\Node\Node;
use App\Manager\Domain\Model\Aggregate\Ring\Ring;
use App\Manager\Domain\Out\Node\CreatorInterface;
use App\Manager\Domain\Out\Node\FinderInterface as NodeFinderInterface;
use App\Manager\Domain\Out\Ring\FinderInterface as RingFinderInterface;
use App\Manager\Infrastructure\Persistence\Mapper\NodeMapper;
use App\Shared\Domain\Const\NodeState;
use App\Shared\Infrastructure\Persistence\Doctrine\Node as NodeEntity;
use App\Shared\Infrastructure\Persistence\Doctrine\VirtualNode as VirtualNodeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NodeEntity>
 */
class NodeRepository extends ServiceEntityRepository implements NodeFinderInterface, RingFinderInterface, CreatorInterface
{
    public function __construct(private readonly VirtualNodeRepository $virtualNodeRepository, ManagerRegistry $registry)
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
            $label,
            new \DateTimeImmutable()
        );

        $em = $this->getEntityManager();
        $em->persist($selfNode);
        $em->flush();

        return NodeMapper::entityToDto($selfNode);
    }

    public function saveNode(Node $node): void
    {
        $nodeEntity = $this->createOrUpdate($node);

        // handle virtual nodes relation
        $virtualNodeEntities = $this->convertVirtualNodeCollectionForDoctrine($node, $nodeEntity);
        $nodeEntity->setVirtualNodes($virtualNodeEntities);

        $this->handleRemovedVirtualNodes($node);

        $em = $this->getEntityManager();
        $em->persist($nodeEntity);
        $em->flush();
    }

    public function getAll(): array
    {
        /** @var array<NodeEntity> $entityArray */
        $entityArray = parent::findAll();

        return array_map(fn (NodeEntity $nodeEntity) => NodeMapper::entityToDto($nodeEntity), $entityArray);
    }

    public function findSelfEntry(): ?Node
    {
        /** @var ?NodeEntity $selfNode */
        $selfNode = $this->findOneBy(['selfEntry' => true]);

        return $selfNode ? NodeMapper::entityToDto($selfNode) : null;
    }

    public function getLocalRing(): Ring
    {
        return new Ring($this->getAll());
    }

    public function searchBy(?bool $seed = null, ?array $states = null): array
    {
        $qb = $this->createQueryBuilder('n');

        if (null !== $seed) {
            $qb->where('n.seed = :seed')->setParameter('seed', $seed);
        }

        if (null !== $states) {
            $qb->andWhere('n.membershipState IN (:states)')->setParameter('states', $states);
        }

        $entityArray = $qb->getQuery()->getResult();

        return array_map(fn (NodeEntity $nodeEntity) => NodeMapper::entityToDto($nodeEntity), $entityArray);
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

    private function handleRemovedVirtualNodes(Node $node): void
    {
        foreach ($node->getRemovedVirtualNodes() as $removedVirtualNode) {
            $this->virtualNodeRepository->remove($removedVirtualNode);
        }
    }
}
