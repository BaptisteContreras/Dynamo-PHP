<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Out\Finder\WorkerNodeFinder;
use App\Manager\Domain\Contract\Out\Repository\WorkerNodeRepositoryInterface;
use App\Manager\Domain\Model\Entity\WorkerNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineWorkerNodeRepository extends ServiceEntityRepository implements WorkerNodeRepositoryInterface, WorkerNodeFinder
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerNode::class);
    }

    public function add(WorkerNode $workerNode, bool $flush): void
    {
        $this->_em->persist($workerNode);

        if ($flush) {
            $this->flushTransaction();
        }
    }

    public function update(WorkerNode $workerNode, bool $flush): void
    {
        if ($flush) {
            $this->flushTransaction();
        }
    }

    public function remove(WorkerNode $workerNode, bool $flush): void
    {
        $this->_em->remove($workerNode);

        if ($flush) {
            $this->flushTransaction();
        }
    }

    public function findOneById(int $id): ?WorkerNode
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('w')
            ->getQuery()
            ->getResult();
    }

    public function findOneByIpAndPort(string $ip, int $port): ?WorkerNode
    {
        return null;
    }

    public function flushTransaction(): void
    {
        $this->_em->flush();
    }
}
