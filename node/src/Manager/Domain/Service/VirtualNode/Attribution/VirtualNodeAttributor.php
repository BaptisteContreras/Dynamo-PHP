<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution;

use App\Manager\Domain\Exception\UnknownVirtualNodeAttributionStrategyException;
use App\Manager\Domain\Model\Node;
use App\Manager\Domain\Model\VirtualNode;

class VirtualNodeAttributor
{
    /**
     * @var array<string, RingSlotSelectionStrategyInterface>
     */
    private readonly array $ringSlotSelectionStrategiesMap;

    /**
     * @param iterable<RingSlotSelectionStrategyInterface> $ringSlotSelectionStategies
     */
    public function __construct(iterable $ringSlotSelectionStategies)
    {
        $ringSlotSelectionStrategiesMap = [];

        foreach ($ringSlotSelectionStategies as $ringSlotSelectionStategy) {
            $ringSlotSelectionStrategiesMap[$ringSlotSelectionStategy->getName()] = $ringSlotSelectionStategy;
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
            new VirtualNode(
                $this->generateSubLabel($node->getLabel(), $index),
                $slot,
                $node
            );
        }
    }

    private function generateSubLabel(string $label, int $index): string
    {
        return sprintf('%s%s', $label, $index + 1);
    }
}
