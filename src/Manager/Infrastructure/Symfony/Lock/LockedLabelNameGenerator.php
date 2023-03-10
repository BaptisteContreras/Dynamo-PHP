<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

use App\Manager\Domain\Exception\LabelNameGeneratorSequenceInitException;
use App\Manager\Domain\Exception\LabelNameGeneratorSequenceInitFailedException;
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
     * @throws CouldNotWriteLockFileException
     * @throws LabelNameGeneratorSequenceInitException
     */
    public function generate(): string
    {
        $lock = $this->lockFactory->createLock(self::SERVICE_LOCK_NAME);

        $lock->acquire(true);

        try {
            if (!file_exists($this->counterFilePath)) {
                throw new LabelNameGeneratorSequenceInitException();
            }

            if (false === ($generatedName = file_get_contents($this->counterFilePath))) {
                throw new CouldNotOpenLockFileException($this->counterFilePath);
            }

            $currentName = $generatedName;
            if (!file_put_contents($this->counterFilePath, ++$generatedName)) {
                throw new CouldNotWriteLockFileException($this->counterFilePath);
            }

            return $currentName;
        } finally {
            $lock->release();
        }
    }

    public function initSequence(): void
    {
        if (!file_put_contents($this->counterFilePath, self::FIRST_NAME)) {
            throw new LabelNameGeneratorSequenceInitFailedException();
        }
    }
}
