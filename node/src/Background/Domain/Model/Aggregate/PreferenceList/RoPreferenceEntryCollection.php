<?php

namespace App\Background\Domain\Model\Aggregate\PreferenceList;

use App\Shared\Domain\Model\Collection\ReadOnlyCollection;

/**
 * @extends ReadOnlyCollection<PreferenceEntry>
 */
class RoPreferenceEntryCollection extends ReadOnlyCollection
{
    protected function getKeyFromElement(mixed $element): string
    {
        return (string) $element->getSlot();
    }

    public function asReadOnly(): static
    {
        return $this;
    }
}
