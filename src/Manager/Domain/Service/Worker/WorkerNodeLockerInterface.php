<?php

namespace App\Manager\Domain\Service\Worker;

use App\Manager\Domain\Exception\LockingFailsException;
use App\Manager\Domain\Model\Dto\WorkerNode;

interface WorkerNodeLockerInterface
{
    /**
     * Put a pessimistic lock for the given worker for the joining action.
     *
     * @throws LockingFailsException
     */
    public function lockWorkerNodeForJoining(WorkerNode $workerNode): void;

    public function unLockWorkerNodeForJoining(WorkerNode $workerNode): void;
}
