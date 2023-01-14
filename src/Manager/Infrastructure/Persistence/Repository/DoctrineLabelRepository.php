<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Out\Finder\LabelFinder;
use App\Manager\Domain\Contract\Out\Repository\LabelRepositoryInterface;
use App\Manager\Domain\Model\Dto\Label;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineLabelRepository extends ServiceEntityRepository implements LabelRepositoryInterface, LabelFinder
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Label::class);
    }

    public function findFree(): array
    {
        return [];
    }

    public function add(Label $label, bool $flush): void
    {
    }

    public function remove(Label $label, bool $flush): void
    {
    }

    public function update(Label $label, bool $flush): void
    {
    }
}
