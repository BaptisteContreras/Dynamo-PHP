<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Out\Repository\WorkerInformationsRepositoryInterface;
use App\Manager\Domain\Model\Dto\WorkerNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineWorkerNodeRepository extends ServiceEntityRepository implements WorkerInformationsRepositoryInterface
{
    /**         Constructor         **/
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkerNode::class);
    }

    /**         Methods         **/
    public function add(WorkerNode $workerInformations, bool $flush): void
    {
        $this->_em->persist($workerInformations);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function update(WorkerNode $workerInformations, bool $flush): void
    {
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(WorkerNode $workerInformations, bool $flush): void
    {
        $this->_em->remove($workerInformations);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findOneById(int $id): ?WorkerNode
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('w')
            ->getQuery()
            ->getResult();
    }
}
