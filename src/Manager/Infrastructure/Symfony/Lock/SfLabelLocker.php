<?php

namespace App\Manager\Infrastructure\Symfony\Lock;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Model\Dto\Label;
use App\Manager\Domain\Service\Label\LabelLockerInterface;
use Symfony\Component\Lock\LockFactory;

class SfLabelLocker extends SfLocker implements LabelLockerInterface
{
    private const ACTION_ASSIGNATION = 'action_assignation';

    public function __construct(
        private readonly LockFactory $lockFactory
    ) {
        parent::__construct(
            $this->lockFactory,
            [
                self::ACTION_ASSIGNATION => [],
            ]
        );
    }

    /**
     * @throws AlreadyLockException
     */
    public function tryLockForAssignation(Label $label): bool
    {
        return $this->tryLockResourceForAction(
            self::ACTION_ASSIGNATION,
            $this->labelToLockKey($label),
            true,
            false
        );
    }

    public function unlockForAssignation(Label $label): void
    {
        $this->tryUnlockWorkerNodeForJoining(self::ACTION_ASSIGNATION, $this->labelToLockKey($label));
    }

    public function unlockSlotsForAssignation(array $labels): void
    {
        foreach ($labels as $label) {
            $this->unlockForAssignation($label);
        }
    }

    private function labelToLockKey(Label $label): string
    {
        return sprintf(
            '%s-%s',
            self::ACTION_ASSIGNATION,
            $label->getId()
        );
    }
}
