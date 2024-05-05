<?php

namespace App\Manager\Domain\Service\VirtualNode\Attribution;

use App\Manager\Domain\Model\VirtualNode;

interface VirtualNodeAttributionStrategyInterface
{
    public function getName(): string;

    /**
     * @param positive-int $numberOfVirtualNode
     *
     * @return array<VirtualNode>
     */
    public function getVirtualNodes(string $label, int $numberOfVirtualNode): array;
}
