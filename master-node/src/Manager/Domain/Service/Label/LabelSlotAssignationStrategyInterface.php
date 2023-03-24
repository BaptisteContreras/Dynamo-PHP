<?php

namespace App\Manager\Domain\Service\Label;

use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;
use App\Manager\Domain\Model\Entity\LabelSlot;

interface LabelSlotAssignationStrategyInterface
{
    /**
     * The strategy must select $nbSlotRequired free label slot to assign to a worker node.
     * The strategy is concurrency safe because it puts a lock on every label returned.
     *
     * /!\ Be aware that the calling object must handle the unlocking of the labels,
     * the strategy cannot do it itself /!\
     *
     * If $throwExceptionOnInvalidRequirement is true, throw an exception if the strategy cannot return enough
     * label slots to assign.
     *
     * @return array<LabelSlot>
     *
     * @throws NotEnoughFreeLabelSlotException
     */
    public function selectSlots(int $nbSlotRequired, bool $throwExceptionOnInvalidRequirement): array;
}
