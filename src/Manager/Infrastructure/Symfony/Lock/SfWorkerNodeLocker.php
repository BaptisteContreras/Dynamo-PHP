<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Exception\LockingFailsException;
use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Manager\Domain\Service\Worker\WorkerNodeLockerInterface;
use Symfony\Component\Lock\LockFactory;

class SfWorkerNodeLocker extends SfLocker implements WorkerNodeLockerInterface
{
    private const ACTION_JOINING = 'action_joining';

    public function __construct(
        private readonly LockFactory $lockFactory
    ) {
        parent::__construct(
            $this->lockFactory,
            [
                self::ACTION_JOINING => [],
            ]
        );
    }

    /**
     * @throws LockingFailsException
     * @throws AlreadyLockException
     */
    public function lockWorkerNodeForJoining(WorkerNode $workerNode): void
    {
        $this->tryLockResourceForAction(
            self::ACTION_JOINING,
            $this->workerNodeToLockKey($workerNode),
            true,
            true
        );
    }

    public function unlockWorkerNodeForJoining(WorkerNode $workerNode): void
    {
        $this->tryUnlockWorkerNodeForJoining(self::ACTION_JOINING, $this->workerNodeToLockKey($workerNode));
    }

    private function workerNodeToLockKey(WorkerNode $workerNode): string
    {
        // We need to hash here because networkAddress and networkPort are data provided by user (even if there are validated)
        return hash('sha256', sprintf(
            '%s-%s-%s',
            self::ACTION_JOINING,
            $workerNode->getNetworkAddress(),
            $workerNode->getNetworkPort()
        ));
    }
}
