<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution;

interface RingSlotSelectionStrategyInterface
{
    public function getName(): string;

    /**
     * @param positive-int $numberOfVirtualNode
     *
     * @return array<int, int>
     */
    public function getVirtualNodes(string $label, int $numberOfVirtualNode): array;
}
