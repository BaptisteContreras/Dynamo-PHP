<?php

namespace App\Manager\Domain\Contract\Out\Repository;

use App\Manager\Domain\Model\Dto\WorkerNode;

interface WorkerInformationsRepositoryInterface
{
    public function add(WorkerNode $workerInformations, bool $flush): void;

    public function remove(WorkerNode $workerInformations, bool $flush): void;

    public function update(WorkerNode $workerInformations, bool $flush): void;

    public function findOneById(int $id): ?WorkerNode;

    /**
     * @return array<WorkerNode>
     */
    public function findAll(): array;
}
