<?php

namespace App\Background\Domain\Model\Aggregate\PreferenceList;

use Symfony\Component\Uid\UuidV7;

class PreferenceEntry
{
    /**
     * @param array<UuidV7> $coordinatorsIds
     */
    public function __construct(
        private int $slot,
        private readonly UuidV7 $ownerId,
        private array $coordinatorsIds,
        private readonly UuidV7 $id = new UuidV7(),
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getOwnerId(): UuidV7
    {
        return $this->ownerId;
    }

    public function getCoordinatorsIds(): array
    {
        return $this->coordinatorsIds;
    }
}
