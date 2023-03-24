<?php

namespace App\Manager\Domain\Service\Worker;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Exception\LockingFailsException;
use App\Manager\Domain\Model\Entity\WorkerNode;

interface WorkerNodeLockerInterface
{
    /**
     * Put a pessimistic lock for the given worker for the join action.
     *
     * If you call this method twice in a row without unlocking the worker node, you will get an exception.
     *
     * @throws LockingFailsException
     * @throws AlreadyLockException
     */
    public function lockWorkerNodeForJoining(WorkerNode $workerNode): void;

    /**
     * Unlock the given worker for the join action if it has been locked.
     * Nothing happens if the worker is not locked.
     */
    public function unlockWorkerNodeForJoining(WorkerNode $workerNode): void;

    /**
     * Put a pessimistic lock for the given worker for the leave action.
     *
     * If you call this method twice in a row without unlocking the worker node, you will get an exception.
     *
     * @throws LockingFailsException
     * @throws AlreadyLockException
     */
    public function lockWorkerNodeForLeaving(WorkerNode $workerNode): void;

    /**
     * Unlock the given worker for the leave action if it has been locked.
     * Nothing happens if the worker is not locked.
     */
    public function unlockWorkerNodeForLeaving(WorkerNode $workerNode): void;
}
