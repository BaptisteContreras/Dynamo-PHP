<?php

namespace App\Background\Domain\Model\Aggregate\PreferenceList;

use App\Shared\Domain\Model\Collection\MutableCollection;
use App\Shared\Domain\Model\Collection\MutableCollectionInterface;

/**
 * @implements MutableCollectionInterface<PreferenceEntry>
 */
class PreferenceEntryCollection extends RoPreferenceEntryCollection implements MutableCollectionInterface
{
    /**
     * @use MutableCollection<PreferenceEntry>
     */
    use MutableCollection;

    public function sortBySlot(): void
    {
        uasort($this->internal, function (PreferenceEntry $a, PreferenceEntry $b) {
            return $a->getSlot() > $b->getSlot() ? 1 : -1;
        });
    }
}
