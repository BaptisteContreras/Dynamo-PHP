<?php

namespace App\Background\Domain\Model\Aggregate\PreferenceList;

class PreferenceList
{
    public function __construct(
        private readonly PreferenceEntryCollection $internalList,
    ) {
        $this->internalList->sortBySlot();
    }

    public function getEntries(): RoPreferenceEntryCollection
    {
        return $this->internalList;
    }
}
