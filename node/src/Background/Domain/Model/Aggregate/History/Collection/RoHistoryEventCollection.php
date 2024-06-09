<?php

namespace App\Background\Domain\Model\Aggregate\History\Collection;

use App\Background\Domain\Model\Aggregate\History\Event;
use App\Shared\Domain\Model\Collection\ReadOnlyCollection;

/**
 * @extends ReadOnlyCollection<Event>
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
