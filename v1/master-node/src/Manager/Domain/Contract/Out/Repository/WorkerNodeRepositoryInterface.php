<?php

namespace App\Manager\Domain\Contract\Out\Repository;

use App\Manager\Domain\Model\Entity\WorkerNode;

interface WorkerNodeRepositoryInterface
{
    /**
     * Add the creation of the worker node to the current transaction.
     * If $flush = true, commit the transaction.
     */
    public function add(WorkerNode $workerNode, bool $flush): void;

    /**
     * Add the removal of the worker node to the current transaction.
     * If $flush = true, commit the transaction.
     */
    public function remove(WorkerNode $workerNode, bool $flush): void;

    /**
     * Add the update of the worker node to the current transaction.
     * If $flush = true, commit the transaction.
     */
    public function update(WorkerNode $workerNode, bool $flush): void;

    /**
     * Commit the transaction.
     */
    public function flushTransaction(): void;
}
