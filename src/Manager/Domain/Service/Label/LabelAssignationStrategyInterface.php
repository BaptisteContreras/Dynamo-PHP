<?php

namespace App\Manager\Domain\Service\Label;

use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;
use App\Manager\Domain\Model\Dto\Label;

interface LabelAssignationStrategyInterface
{
    /**
     * The strategy must select $nbSlotRequired free label slot to assign to a worker node.
     *
     * // TODO add lock here ?
     *
     * If $throwExceptionOnInvalidRequirement is true, throw an exception if the strategy cannot return enough
     * label slots to assign.
     *
     * @return array<Label>
     *
     * @throws NotEnoughFreeLabelSlotException
     */
    public function selectSlots(int $nbSlotRequired, bool $throwExceptionOnInvalidRequirement): array;
}
