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
    private const ACTION_LEAVING = 'action_leaving';

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
            $this->workerNodeToLockKey($workerNode, self::ACTION_JOINING),
            true,
            true
        );
    }

    public function unlockWorkerNodeForJoining(WorkerNode $workerNode): void
    {
        $this->tryUnlockWorkerNodeForJoining(self::ACTION_JOINING, $this->workerNodeToLockKey($workerNode, self::ACTION_JOINING));
    }

    public function lockWorkerNodeForLeaving(WorkerNode $workerNode): void
    {
        $this->tryLockResourceForAction(
            self::ACTION_LEAVING,
            $this->workerNodeToLockKey($workerNode, self::ACTION_LEAVING),
            true,
            true
        );
    }

    public function unlockWorkerNodeForLeaving(WorkerNode $workerNode): void
    {
        $this->tryUnlockWorkerNodeForJoining(self::ACTION_LEAVING, $this->workerNodeToLockKey($workerNode, self::ACTION_LEAVING));
    }

    private function workerNodeToLockKey(WorkerNode $workerNode, string $action): string
    {
        // We need to hash here because networkAddress and networkPort are data provided by user (even if there are validated)
        return hash('sha256', sprintf(
            '%s-%s-%s',
            $action,
            $workerNode->getNetworkAddress(),
            $workerNode->getNetworkPort()
        ));
    }
}
