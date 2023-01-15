<?php

namespace App\Manager\Domain\Service\Label;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Model\Entity\LabelSlot;

interface LabelLockerInterface
{
    /**
     * Try to lock the label and returns true if it succeeds. Instead, of throwing an exception if the lock fail, it
     * returns false (and true otherwise...).
     * If you call this method twice in a row without unlocking the worker node, you will get an exception.
     *
     * @throws AlreadyLockException
     */
    public function tryLockForAssignation(LabelSlot $label): bool;

    public function unlockForAssignation(LabelSlot $label): void;

    /**
     * @param array<LabelSlot> $labels
     */
    public function unlockSlotsForAssignation(array $labels): void;
}
