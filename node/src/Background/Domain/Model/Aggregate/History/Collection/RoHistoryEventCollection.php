<?php

namespace App\Background\Domain\Model\Aggregate\History\Collection;

use App\Background\Domain\Model\Aggregate\History\HistoryEvent;
use App\Shared\Domain\Model\Collection\ReadOnlyCollection;

/**
 * @extends ReadOnlyCollection<HistoryEvent>
 */
class RoHistoryEventCollection extends ReadOnlyCollection
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
