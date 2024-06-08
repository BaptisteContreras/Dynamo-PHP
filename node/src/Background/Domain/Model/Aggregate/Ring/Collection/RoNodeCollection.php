<?php

namespace App\Background\Domain\Model\Aggregate\Ring\Collection;

use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Shared\Domain\Model\Collection\ReadOnlyCollection;

/**
 * @extends ReadOnlyCollection<Node>
 */
class RoNodeCollection extends ReadOnlyCollection
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
