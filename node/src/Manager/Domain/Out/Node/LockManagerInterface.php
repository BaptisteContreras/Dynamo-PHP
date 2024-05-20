<?php

namespace App\Manager\Domain\Out\Node;

use App\Shared\Domain\Exception\AlreadyLockException;
use App\Shared\Domain\Exception\LockingFailsException;

interface LockManagerInterface
{
    /**
     * @throws LockingFailsException
     * @throws AlreadyLockException
     */
    public function lockJoinAction(): void;

    public function unlockJoinAction(): void;
}
