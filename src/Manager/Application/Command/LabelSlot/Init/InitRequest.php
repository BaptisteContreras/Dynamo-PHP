<?php

namespace App\Manager\Application\Command\LabelSlot\Init;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

final class InitRequest
{
    #[NotBlank]
    #[Choice(callback: [LabelsSlotsAllocationStrategy::class, 'getStrChoices'])]
    private ?string $allocationStrategyName = null;

    public function getAllocationStrategyName(): string
    {
        return $this->allocationStrategyName;
    }

    public function setAllocationStrategyName(?string $allocationStrategyName): void
    {
        $this->allocationStrategyName = $allocationStrategyName;
    }

    public function getAllocationStrategyNameEnum(): LabelsSlotsAllocationStrategy
    {
        return LabelsSlotsAllocationStrategy::from($this->allocationStrategyName ?? '');
    }
}
