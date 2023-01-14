<?php

namespace App\Manager\Domain\Service\Worker;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Exception\LockingFailsException;
use App\Manager\Domain\Model\Dto\WorkerNode;

interface WorkerNodeLockerInterface
{
    /**
     * Put a pessimistic lock for the given worker for the joining action.
     *
     * If you call this method twice in a row without unlocking the worker node, you will get an exception
     *
     * @throws LockingFailsException
     * @throws AlreadyLockException
     */
    public function lockWorkerNodeForJoining(WorkerNode $workerNode): void;

    public function unlockWorkerNodeForJoining(WorkerNode $workerNode): void;
}
