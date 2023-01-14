<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

use App\Manager\Domain\Service\Label\LabelNameGeneratorInterface;
use Symfony\Component\Lock\LockFactory;

class LockedLabelNameGenerator implements LabelNameGeneratorInterface
{
    private const SERVICE_LOCK_NAME = 'LockedLabelNameGenerator';
    private const FIRST_NAME = 'A';

    public function __construct(
        private readonly LockFactory $lockFactory,
        private readonly string $counterFilePath
    ) {
    }

    /**
     * @throws CouldNotOpenLockFileException
     * @throws CouldNotReadLockFileException
     * @throws CouldNotWriteLockFileException
     */
    public function generate(): string
    {
        $lock = $this->lockFactory->createLock(self::SERVICE_LOCK_NAME);

        $lock->acquire(true);

        $counterFilePathFd = null;

        try {
            if (!file_exists($this->counterFilePath)) {
                if (!$counterFilePathFd = fopen($this->counterFilePath, 'w')) {
                    throw new CouldNotOpenLockFileException($this->counterFilePath);
                }

                $generatedName = self::FIRST_NAME;
            } else {
                if (!$counterFilePathFd = fopen($this->counterFilePath, 'r+')) {
                    throw new CouldNotOpenLockFileException($this->counterFilePath);
                }

                if (!$generatedName = fgets($counterFilePathFd)) {
                    throw new CouldNotReadLockFileException($this->counterFilePath);
                }

                ++$generatedName;
            }

            if (!fwrite($counterFilePathFd, $generatedName)) {
                throw new CouldNotWriteLockFileException($this->counterFilePath);
            }

            return $generatedName;
        } finally {
            $lock->release();

            if ($counterFilePathFd) {
                fclose($counterFilePathFd);
            }
        }
    }
}
