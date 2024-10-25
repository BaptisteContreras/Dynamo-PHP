<?php

namespace App\Foreground\Infrastructure\Persistence\Mapper;

use App\Foreground\Domain\Model\Aggregate\PreferenceList\PreferenceEntry;
use App\Shared\Infrastructure\Persistence\Doctrine\PreferenceListEntry;

final class PreferenceListEntryMapper
{
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
     * @param array<PreferenceListEntry> $entityArray
     *
     * @return array<PreferenceEntry>
     */
    public static function entityArrayToDtoArray(array $entityArray): array
    {
        return array_map(fn (PreferenceListEntry $dto) => self::entityToDto($dto), $entityArray);
    }
}
