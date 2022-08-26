<?php

namespace App\Manager\Infrastructure\Persistence\Repository;

use App\Manager\Domain\Contract\Repository\WorkerInformationsRepositoryInterface;
use App\Manager\Domain\Model\Dto\WorkerInformations;

class DoctrineWorkerInformationsRepository implements WorkerInformationsRepositoryInterface
{
    /**         Properties         **/
    /**         Constructor         **/
    /**         Methods         **/
    /**         Accessors         **/
    public function add(WorkerInformations $workerInformations): void
    {
        // TODO: Implement add() method.
    }

    public function remove(WorkerInformations $workerInformations): void
    {
        // TODO: Implement remove() method.
    }

    public function findOneById(int $id): ?WorkerInformations
    {
        // TODO: Implement findOneById() method.
    }

    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }
}
