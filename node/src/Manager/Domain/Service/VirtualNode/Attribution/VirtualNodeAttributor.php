<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution;

use App\Manager\Domain\Exception\UnknownVirtualNodeAttributionStrategyException;
use App\Manager\Domain\Model\Aggregate\Node\Node;

class VirtualNodeAttributor
{
    /**
     * @var array<string, RingSlotSelectionStrategyInterface>
     */
    private readonly array $ringSlotSelectionStrategiesMap;

    /**
     * @param iterable<RingSlotSelectionStrategyInterface> $ringSlotSelectionStrategies
     */
    public function __construct(iterable $ringSlotSelectionStrategies)
    {
        $ringSlotSelectionStrategiesMap = [];

        foreach ($ringSlotSelectionStrategies as $ringSlotSelectionStrategy) {
            $ringSlotSelectionStrategiesMap[$ringSlotSelectionStrategy->getName()] = $ringSlotSelectionStrategy;
        }

        $this->ringSlotSelectionStrategiesMap = $ringSlotSelectionStrategiesMap;
    }

    /**
     * @throws UnknownVirtualNodeAttributionStrategyException
     */
    public function attributeVirtualNodes(Node $node, string $strategy): void
    {
        $strategy = $this->ringSlotSelectionStrategiesMap[$strategy] ?? throw new UnknownVirtualNodeAttributionStrategyException($strategy);

        $slots = $strategy->getVirtualNodes($node->getLabel(), $node->getWeight());

        foreach ($slots as $index => $slot) {
            $node->addVirtualNode(
                $this->generateSubLabel($node->getLabel(), $index),
                $slot
            );
        }
    }

    private function generateSubLabel(string $label, int $index): string
    {
        return sprintf('%s%s', $label, $index + 1);
    }
}
