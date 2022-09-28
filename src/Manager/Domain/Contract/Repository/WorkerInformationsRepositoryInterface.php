<?php

namespace App\Manager\Domain\Contract\Repository;

use App\Manager\Domain\Model\Dto\WorkerInformations;

interface WorkerInformationsRepositoryInterface
{
    public function add(WorkerInformations $workerInformations, bool $flush): void;

    public function remove(WorkerInformations $workerInformations, bool $flush): void;

    public function update(WorkerInformations $workerInformations, bool $flush): void;

    public function findOneById(int $id): ?WorkerInformations;

    /**
     * @return array<WorkerInformations>
     */
    public function findAll(): array;
}
