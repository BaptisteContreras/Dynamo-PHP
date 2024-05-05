<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution;

use App\Manager\Domain\Exception\UnknownVirtualNodeAttributionStrategyException;
use App\Manager\Domain\Model\Node;

class VirtualNodeAttributor
{
    /**
     * @var array<string, VirtualNodeAttributionStrategyInterface>
     */
    private readonly array $attributionStrategiesMap;

    /**
     * @param iterable<VirtualNodeAttributionStrategyInterface> $attributionStrategies
     */
    public function __construct(iterable $attributionStrategies)
    {
        $attributionStrategiesMap = [];

        foreach ($attributionStrategies as $attributionStrategy) {
            $attributionStrategiesMap[$attributionStrategy->getName()] = $attributionStrategy;
        }

        $this->attributionStrategiesMap = $attributionStrategiesMap;
    }

    /**
     * @throws UnknownVirtualNodeAttributionStrategyException
     */
    public function attributeVirtualNodes(Node $node, string $strategy): void
    {
        $strategy = $this->attributionStrategiesMap[$strategy] ?? throw new UnknownVirtualNodeAttributionStrategyException($strategy);

        $virtualNodes = $strategy->getVirtualNodes($node->getLabel(), $node->getWeight());
    }
}
