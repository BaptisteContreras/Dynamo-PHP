<?php

namespace App\Manager\Domain\Contract\Repository;

use App\Manager\Domain\Model\Dto\WorkerInformations;

interface WorkerInformationsRepositoryInterface
{
    public function add(WorkerInformations $workerInformations): void;

    public function remove(WorkerInformations $workerInformations): void;

    public function findOneById(int $id): ?WorkerInformations;

    /**
     * @return array<WorkerInformations>
     */
    public function findAll(): array;
}
