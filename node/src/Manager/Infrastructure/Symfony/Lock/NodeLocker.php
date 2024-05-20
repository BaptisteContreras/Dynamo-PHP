<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

use App\Manager\Domain\Out\Node\LockManagerInterface;
use App\Shared\Infrastructure\Symfony\Lock\Locker;
use Symfony\Component\Lock\LockFactory;

class NodeLocker extends Locker implements LockManagerInterface
{
    private const LOCK_NAMESPACE = 'manager.node';

    private const ACTION_JOIN = 'join';

    public function __construct(LockFactory $lockFactory)
    {
        parent::__construct($lockFactory, self::LOCK_NAMESPACE);
    }

    public function lockJoinAction(): void
    {
        $this->tryLockResourceForAction(
            self::ACTION_JOIN, 'this', true, true
        );
    }

    public function unlockJoinAction(): void
    {
        $this->tryUnlockWorkerNodeForJoining(self::ACTION_JOIN, 'this');
    }
}
