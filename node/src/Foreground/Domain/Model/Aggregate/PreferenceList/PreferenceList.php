<?php

namespace App\Foreground\Domain\Model\Aggregate\PreferenceList;

final readonly class PreferenceList
{
    /**
     * @var array<PreferenceEntry>
     */
    private array $entries;

    /**
     * @param array<PreferenceEntry> $entries
     */
    public function __construct(
        array $entries,
    ) {
        uasort($entries, function (PreferenceEntry $a, PreferenceEntry $b) {
            return $a->getSlot() > $b->getSlot() ? 1 : -1;
        });
        $this->entries = $entries;
    }

    /**
     * @return array<PreferenceEntry>
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    public function getEntry(int $slot): PreferenceEntry
    {
        return $this->entries[$slot];
    }
}
