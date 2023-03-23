<?php

namespace App\Manager\Application\Command\Ring\Init;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

final class InitRequest
{
    #[NotBlank]
    #[Choice(callback: [LabelsSlotsAllocationStrategy::class, 'getStrChoices'])]
    private ?string $allocationStrategyName = null;

    public function getAllocationStrategyName(): ?string
    {
        return $this->allocationStrategyName;
    }

    public function setAllocationStrategyName(?string $allocationStrategyName): void
    {
        $this->allocationStrategyName = $allocationStrategyName;
    }

    #[OA\Property(
        title: 'allocationStrategyName',
        description: 'The algorithm to init the label slots',
    )]
    #[Groups('OA')]
    public function getAllocationStrategyNameEnum(): LabelsSlotsAllocationStrategy
    {
        return LabelsSlotsAllocationStrategy::from($this->allocationStrategyName ?? '');
    }
}
