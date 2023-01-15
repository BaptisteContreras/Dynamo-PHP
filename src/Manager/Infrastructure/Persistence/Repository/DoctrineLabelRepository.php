<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Out\Finder\LabelFinder;
use App\Manager\Domain\Contract\Out\Repository\LabelRepositoryInterface;
use App\Manager\Domain\Model\Entity\LabelSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineLabelRepository extends ServiceEntityRepository implements LabelRepositoryInterface, LabelFinder
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
    }

    public function remove(LabelSlot $label, bool $flush): void
    {
    }

    public function update(LabelSlot $label, bool $flush): void
    {
    }
}
