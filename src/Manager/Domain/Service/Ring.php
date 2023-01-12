<?php

namespace App\Manager\Domain\Service;

use App\Manager\Domain\Model\Dto\WorkerNode;

class Ring
{
    /**
     * Try to make the given worker node join the pool.
     * During this method, a lock is set and no other worker node with the same network address and network port can try
     * to join the pool. As it is a pessimist lock, it throws an exception without waiting.
     *
     * ########################################## Conditions to join the pool ##########################################
     *  - No other worker node with the same network address and network port exists in the ring
     *  - The ring is not full (i.e. the maximum size of the ring depends on the number of labels) -> for the moment
     * the maximum size of the ring is fixed to 360
     */
    public function join(WorkerNode $workerNode): void
    {
    }
}
