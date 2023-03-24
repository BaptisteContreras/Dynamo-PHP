<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Exception\LockingFailsException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

abstract class SfLocker
{
    /**
     * @param array<string, array<string, LockInterface>> $lockMap
     */
    public function __construct(
        private readonly LockFactory $lockFactory,
        private array $lockMap = []
    ) {
    }

    /**
     * @throws LockingFailsException if $throwExceptionIfAcquireFails is true
     * @throws AlreadyLockException  if $throwExceptionIfAlreadyLocked is true
     */
    protected function tryLockResourceForAction(
        string $action,
        string $resourceKey,
        bool $throwExceptionIfAlreadyLocked,
        bool $throwExceptionIfAcquireFails
    ): bool {
        if ($this->isLockedForAction($action, $resourceKey)) {
            if ($throwExceptionIfAlreadyLocked) {
                throw new AlreadyLockException();
            }

            return false;
        }

        $lock = $this->lockFactory->createLock($resourceKey);

        if (!$lock->acquire()) {
            if ($throwExceptionIfAcquireFails) {
                throw new LockingFailsException();
            }

            return false;
        }

        $this->addLockForAction($action, $resourceKey, $lock);

        return true;
    }

    protected function tryUnlockWorkerNodeForJoining(string $action, string $resourceKey): bool
    {
        if ($this->isLockedForAction($action, $resourceKey)) {
            $lock = $this->getLockForAction($action, $resourceKey);

            $lock->release();

            $this->removeLockForAction($action, $resourceKey);

            return true;
        }

        return false;
    }

    private function isLockedForAction(string $action, string $resourceLockKey): bool
    {
        return isset($this->lockMap[$action][$resourceLockKey]);
    }

    private function addLockForAction(string $action, string $resourceLockKey, LockInterface $lock): void
    {
        if (!isset($this->lockMap[$action])) {
            $this->lockMap[$action] = [];
        }

        $this->lockMap[$action][$resourceLockKey] = $lock;
    }

    private function getLockForAction(string $action, string $resourceLockKey): LockInterface
    {
        return $this->lockMap[$action][$resourceLockKey];
    }

    private function removeLockForAction(string $action, string $resourceLockKey): void
    {
        unset($this->lockMap[$action][$resourceLockKey]);
    }
}
