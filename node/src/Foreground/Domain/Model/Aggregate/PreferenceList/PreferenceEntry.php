<?php

namespace App\Foreground\Domain\Model\Aggregate\PreferenceList;

use Symfony\Component\Uid\UuidV7;

final readonly class PreferenceEntry
{
    /**
     * @param array<UuidV7> $coordinatorsIds
     * @param array<UuidV7> $othersNodesIds
     */
    public function __construct(
        private int $slot,
        private UuidV7 $ownerId,
        private array $coordinatorsIds,
        private array $othersNodesIds,
        private UuidV7 $id,
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
