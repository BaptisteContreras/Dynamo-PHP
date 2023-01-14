<?php

namespace App\Manager\Domain\Service\Label;

use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Model\Dto\Label;

interface LabelLockerInterface
{
    /**
     * Try to lock the label and returns true if it succeeds. Instead, of throwing an exception if the lock fail, it
     * returns false (and true otherwise...).
     * If you call this method twice in a row without unlocking the worker node, you will get an exception.
     *
     * @throws AlreadyLockException
     */
    public function tryLockForAssignation(Label $label): bool;

    public function unlockForAssignation(Label $label): void;

    /**
     * @param array<Label> $labels
     */
    public function unlockSlotsForAssignation(array $labels): void;
}
