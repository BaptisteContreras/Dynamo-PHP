<?php

namespace App\Manager\Application\Query\PreferenceList\ViewModel\Dto;

use OpenApi\Attributes as OA;

class JsonPreferenceEntryResponse
{
    /**
     * @param array<string> $coordinatorsIds
     * @param array<string> $othersNodesIds
     */
    public function __construct(
        private int $slot,
        private readonly string $ownerId,
        private array $coordinatorsIds,
        private array $othersNodesIds,
    ) {
    }

    #[OA\Property(
        example: 42
    )]
    public function getSlot(): int
    {
        return $this->slot;
    }

    #[OA\Property(
        example: 'abcf3554-c788-7a58-a441-89421dabad90'
    )]
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @return array<string>
     */
    #[OA\Property(
        example: '["0191d75a-c586-715f-8a2f-58a638d8e053"]'
    )]
    public function getCoordinatorsIds(): array
    {
        return $this->coordinatorsIds;
    }

    /**
     * @return array<string>
     */
    #[OA\Property(
        example: '["0191d75a-eb78-76ba-af80-aa98170553fd"]'
    )]
    public function getOthersNodesIds(): array
    {
        return $this->othersNodesIds;
    }
}
