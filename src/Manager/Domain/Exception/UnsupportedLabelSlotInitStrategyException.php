<?php

namespace App\Manager\Domain\Exception;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;

class UnsupportedLabelSlotInitStrategyException extends DomainException
{
    public function __construct(LabelsSlotsAllocationStrategy $allocationStrategy)
    {
        parent::__construct(sprintf('%s is not supported', $allocationStrategy->value));
    }
}
