<?php

namespace App\Background\Domain\Model\Aggregate\PreferenceList;

class PreferenceList
{
    public function __construct(
        private readonly PreferenceEntryCollection $internalList
    ) {
    }

    public function getEntries(): RoPreferenceEntryCollection
    {
        return $this->internalList;
    }
}
