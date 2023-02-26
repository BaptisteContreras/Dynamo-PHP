<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Out\Counter\LabelCounter;
use App\Manager\Domain\Contract\Out\Finder\LabelFinder;
use App\Manager\Domain\Contract\Out\Repository\LabelRepositoryInterface;
use App\Manager\Domain\Model\Entity\LabelSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineLabelRepository extends ServiceEntityRepository implements LabelRepositoryInterface, LabelFinder, LabelCounter
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LabelSlot::class);
    }

    public function findFree(): array
    {
        return [];
    }

    public function add(LabelSlot $label, bool $flush): void
    {
        $this->_em->persist($label);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(LabelSlot $label, bool $flush): void
    {
    }

    public function update(LabelSlot $label, bool $flush): void
    {
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

    public function bulkAdd(array $labels): void
    {
        foreach ($labels as $label) {
            $this->add($label, false);
        }

        $this->_em->flush();
    }
}
