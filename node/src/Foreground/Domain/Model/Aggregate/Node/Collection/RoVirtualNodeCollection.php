<?php

namespace App\Foreground\Domain\Model\Aggregate\Node\Collection;

use App\Foreground\Domain\Model\Aggregate\Node\VirtualNode;
use App\Shared\Domain\Model\Collection\ReadOnlyCollection;

/**
 * @extends ReadOnlyCollection<VirtualNode>
 */
class RoVirtualNodeCollection extends ReadOnlyCollection
{
    protected function getKeyFromElement(mixed $element): string
    {
        return $element->getId()->toRfc4122();
    }

    public function asReadOnly(): static
    {
        return $this;
    }
}
