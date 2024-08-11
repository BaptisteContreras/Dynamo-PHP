<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Persistence\Type\OrderedUuidV7JsonArray;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[Entity]
class PreferenceListEntry
{
    /**
     * @param array<int, UuidV7> $coordinatorsIds
     * @param array<int, UuidV7> $othersNodesIds
     */
    public function __construct(
        #[Column(type: Types::INTEGER, unique: true)] private int $slot,
        #[Column(type: UuidType::NAME)] private UuidV7 $ownerId,
        #[Column(type: OrderedUuidV7JsonArray::TYPE)] private array $coordinatorsIds,
        #[Column(type: OrderedUuidV7JsonArray::TYPE)] private array $othersNodesIds,
        #[Id] #[Column(type: UuidType::NAME, unique: true)] private UuidV7 $id = new UuidV7()
    ) {
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function setSlot(int $slot): void
    {
        $this->slot = $slot;
    }

    public function getOwnerId(): UuidV7
    {
        return $this->ownerId;
    }

    public function setOwnerId(UuidV7 $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return array<int, UuidV7>
     */
    public function getCoordinatorsIds(): array
    {
        return $this->coordinatorsIds;
    }

    /**
     * @param array<int, UuidV7> $coordinatorsIds
     */
    public function setCoordinatorsIds(array $coordinatorsIds): void
    {
        $this->coordinatorsIds = $coordinatorsIds;
    }

    /**
     * @return array<int, UuidV7>
     */
    public function getOthersNodesIds(): array
    {
        return $this->othersNodesIds;
    }

    /**
     * @param array<int, UuidV7> $othersNodesIds
     */
    public function setOthersNodesIds(array $othersNodesIds): void
    {
        $this->othersNodesIds = $othersNodesIds;
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function setId(UuidV7 $id): void
    {
        $this->id = $id;
    }
}
