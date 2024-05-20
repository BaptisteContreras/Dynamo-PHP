<?php

namespace App\Shared\Infrastructure\Symfony\Lock;

use App\Shared\Domain\Exception\AlreadyLockException;
use App\Shared\Domain\Exception\LockingFailsException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

abstract class Locker
{
    /**
     * @param array<string, array<string, LockInterface>> $lockMap
     */
    public function __construct(
        private readonly LockFactory $lockFactory,
        private readonly string $lockNamespace,
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
        $realLockKey = $this->getRealLockKey($action, $resourceKey);

        if ($this->isLockedForAction($action, $realLockKey)) {
            if ($throwExceptionIfAlreadyLocked) {
                throw new AlreadyLockException();
            }

            return false;
        }

        $lock = $this->lockFactory->createLock($realLockKey);

        if (!$lock->acquire()) {
            if ($throwExceptionIfAcquireFails) {
                throw new LockingFailsException();
            }

            return false;
        }

        $this->addLockForAction($action, $realLockKey, $lock);

        return true;
    }

    protected function tryUnlockWorkerNodeForJoining(string $action, string $resourceKey): bool
    {
        $realLockKey = $this->getRealLockKey($action, $resourceKey);

        if (!$this->isLockedForAction($action, $realLockKey)) {
            return false;
        }

        $lock = $this->getLockForAction($action, $realLockKey);

        $lock->release();

        $this->removeLockForAction($action, $realLockKey);

        return true;
    }

    private function isLockedForAction(string $action, string $realLockKey): bool
    {
        return isset($this->lockMap[$action][$realLockKey]);
    }

    private function addLockForAction(string $action, string $realLockKey, LockInterface $lock): void
    {
        if (!isset($this->lockMap[$action])) {
            $this->lockMap[$action] = [];
        }

        $this->lockMap[$action][$realLockKey] = $lock;
    }

    private function getLockForAction(string $action, string $realLockKey): LockInterface
    {
        return $this->lockMap[$action][$realLockKey];
    }

    private function removeLockForAction(string $action, string $realLockKey): void
    {
        unset($this->lockMap[$action][$realLockKey]);
    }

    private function getRealLockKey(string $action, string $resourceKey): string
    {
        return hash('sha256', sprintf(
            '%s-%s-%s',
            $this->lockNamespace,
            $action,
            $resourceKey
        ));
    }
}
