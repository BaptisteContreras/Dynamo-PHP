<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Out\Counter\LabelSlotCounterInterface;
use App\Manager\Domain\Contract\Out\Finder\LabelSlotFinderInterface;
use App\Manager\Domain\Contract\Out\Repository\LabelSlotRepositoryInterface;
use App\Manager\Domain\Model\Entity\LabelSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineInterfaceLabelSlotRepositoryInterfaceSlotSlot extends ServiceEntityRepository implements LabelSlotRepositoryInterface, LabelSlotFinderInterface, LabelSlotCounterInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabelSlot::class);
    }

    public function findFree(): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.name IS NULL')
            ->getQuery()
            ->getResult();
    }

    public function add(LabelSlot $label, bool $flush): void
    {
        $this->_em->persist($label);

        if ($flush) {
            $this->flushTransaction();
        }
    }

    public function remove(LabelSlot $label, bool $flush): void
    {
        $this->_em->remove($label);

        if ($flush) {
            $this->flushTransaction();
        }
    }

    public function update(LabelSlot $label, bool $flush): void
    {
        if ($flush) {
            $this->flushTransaction();
        }
    }

    public function findAllSlots(): array
    {
        return $this->findAll();
    }

    public function countAll(): int
    {
        return count($this->findAll());
    }

    public function countFree(): int
    {
        return count($this->findFree());
    }

    public function bulkAdd(array $labels, bool $flush): void
    {
        foreach ($labels as $label) {
            $this->add($label, false);
        }

        $this->flushTransaction();
    }

    public function flushTransaction(): void
    {
        $this->_em->flush();
    }
}
