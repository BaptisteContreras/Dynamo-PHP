<?php

namespace App\Manager\Domain\Model\Aggregate\PreferenceList;

use Symfony\Component\Uid\UuidV7;

class PreferenceEntry
{
    /**
     * @param array<UuidV7> $coordinatorsIds
     * @param array<UuidV7> $othersNodesIds
     */
    public function __construct(
        private int $slot,
        private readonly UuidV7 $ownerId,
        private array $coordinatorsIds,
        private array $othersNodesIds,
        private readonly UuidV7 $id = new UuidV7(),
    ) {
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getOwnerId(): UuidV7
    {
        return $this->ownerId;
    }

    /**
     * @return array<UuidV7>
     */
    public function getCoordinatorsIds(): array
    {
        return $this->coordinatorsIds;
    }

    /**
     * @return array<UuidV7>
     */
    public function getOthersNodesIds(): array
    {
        return $this->othersNodesIds;
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }
}
