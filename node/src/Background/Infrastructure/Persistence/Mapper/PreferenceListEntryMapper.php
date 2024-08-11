<?php

namespace App\Background\Infrastructure\Persistence\Mapper;

use App\Background\Domain\Model\Aggregate\PreferenceList\PreferenceEntry;
use App\Shared\Infrastructure\Persistence\Doctrine\PreferenceListEntry;

final class PreferenceListEntryMapper
{
    public static function dtoToEntity(PreferenceEntry $dto): PreferenceListEntry
    {
        return new PreferenceListEntry(
            $dto->getSlot(),
            $dto->getOwnerId(),
            $dto->getCoordinatorsIds(),
            $dto->getOthersNodesIds(),
            $dto->getId()
        );
    }

    public static function entityToDto(PreferenceListEntry $entity): PreferenceEntry
    {
        return new PreferenceEntry(
            $entity->getSlot(),
            $entity->getOwnerId(),
            $entity->getCoordinatorsIds(),
            $entity->getOthersNodesIds(),
            $entity->getId()
        );
    }

    /**
     * @param array<PreferenceEntry> $dtoArray
     *
     * @return array<PreferenceListEntry>
     */
    public static function dtoArrayToEntityArray(array $dtoArray): array
    {
        return array_map(fn (PreferenceEntry $dto) => self::dtoToEntity($dto), $dtoArray);
    }

    /**
     * @param array<PreferenceListEntry> $entityArray
     *
     * @return array<PreferenceEntry>
     */
    public static function entityArrayToDtoArray(array $entityArray): array
    {
        return array_map(fn (PreferenceListEntry $dto) => self::entityToDto($dto), $entityArray);
    }
}
