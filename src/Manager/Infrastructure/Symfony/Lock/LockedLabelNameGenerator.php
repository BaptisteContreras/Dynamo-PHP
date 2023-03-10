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

        try {
            if (!file_exists($this->counterFilePath)) {
                $generatedName = self::FIRST_NAME;
            } else {
                if (false === ($generatedName = file_get_contents($this->counterFilePath))) {
                    throw new CouldNotOpenLockFileException($this->counterFilePath);
                }

                ++$generatedName;
            }

            if (!file_put_contents($this->counterFilePath, $generatedName)) {
                throw new CouldNotWriteLockFileException($this->counterFilePath);
            }

            return $generatedName;
        } finally {
            $lock->release();
        }
    }
}
