<?php

namespace App\Manager\Domain\Contract\Out\Repository;

use App\Manager\Domain\Model\Dto\WorkerNode;

interface WorkerNodeRepositoryInterface
{
    public function add(WorkerNode $workerNode, bool $flush): void;

    public function remove(WorkerNode $workerNode, bool $flush): void;

    public function update(WorkerNode $workerNode, bool $flush): void;
}
