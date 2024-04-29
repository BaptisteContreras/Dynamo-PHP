<?php

namespace App\Manager\Domain\Service\Label\Init;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;
use App\Manager\Domain\Contract\Out\Repository\LabelSlotRepositoryInterface;
use App\Manager\Domain\Model\Entity\LabelSlot;

class EqualInitStrategy implements LabelSlotInitStrategyInterface
{
    private const SIZE = 1; // TODO should be a parameter later
    private const MAX = 360; // TODO should be a parameter later

    public function __construct(
        private readonly LabelSlotRepositoryInterface $labelRepository
    ) {
    }

    public function createSlots(): void
    {
        $nbSlotsToCreate = self::MAX / self::SIZE;
        $labelsToCreate = [];

        for ($i = 0; $i < $nbSlotsToCreate; ++$i) {
            $labelsToCreate[] = new LabelSlot($i, self::SIZE);
        }

        $this->labelRepository->bulkAdd($labelsToCreate, true);
    }

    public function supports(LabelsSlotsAllocationStrategy $allocationStrategy): bool
    {
        return LabelsSlotsAllocationStrategy::EQUAL_SIZE === $allocationStrategy;
    }
}
