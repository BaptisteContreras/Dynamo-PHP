<?php

namespace App\Manager\Domain\Service\Label\Init;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;

interface LabelSlotInitStrategyInterface
{
    /**
     * Creates the label slots in the storage.
     *
     * If the implementation needs parameters for this task, it must retrieve them with DI
     * /!\ No check is done on the storage to verify if there already are some slots /!\
     * /!\ No concurrent safe /!\
     */
    public function createSlots(): void;

    public function supports(LabelsSlotsAllocationStrategy $allocationStrategy): bool;
}
