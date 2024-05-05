<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution\Strategy;

use App\Manager\Domain\Service\VirtualNode\Attribution\VirtualNodeAttributionStrategyInterface;

class RandomStrategy implements VirtualNodeAttributionStrategyInterface
{
    final public const string NAME = 'random';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getVirtualNodes(string $label, int $numberOfVirtualNode): array
    {
        return [];
    }
}
