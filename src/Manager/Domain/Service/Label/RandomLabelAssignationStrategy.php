<?php

namespace App\Manager\Domain\Service\Label;

use App\Manager\Domain\Contract\Out\Finder\LabelFinderInterface;
use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;

/**
 * This is not a very smart strategy to assign label, but it does the job for testing so..
 */
class RandomLabelAssignationStrategy implements LabelAssignationStrategyInterface
{
    public function __construct(
        private readonly LabelFinderInterface $labelFinder,
        private readonly LabelLockerInterface $labelLocker
    ) {
    }

    public function selectSlots(int $nbSlotRequired, bool $throwExceptionOnInvalidRequirement): array
    {
        $freeSlots = $this->labelFinder->findFree();

        shuffle($freeSlots);

        $slotReserved = [];
        $nbSlotReserved = 0;

        while (($currentSlot = current($freeSlots)) && $nbSlotReserved < $nbSlotRequired) {
            next($freeSlots);
            if ($this->labelLocker->tryLockForAssignation($currentSlot)) {
                $slotReserved[] = $currentSlot;
                ++$nbSlotReserved;
            }
        }

        if ($throwExceptionOnInvalidRequirement && $nbSlotRequired !== $nbSlotReserved) {
            $this->labelLocker->unlockSlotsForAssignation($slotReserved);

            throw new NotEnoughFreeLabelSlotException($nbSlotRequired, $nbSlotReserved);
        }

        return $slotReserved;
    }
}
