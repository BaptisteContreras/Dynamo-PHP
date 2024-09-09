<?php

namespace App\Manager\Application\Query\PreferenceList\ViewModel;

use App\Manager\Application\Query\PreferenceList\ViewModel\Dto\JsonPreferenceEntryResponse;
use App\Manager\Domain\Model\Aggregate\PreferenceList\PreferenceEntry;
use App\Manager\Domain\Model\Aggregate\PreferenceList\PreferenceList;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Uid\UuidV7;

#[OA\Schema(
    title: 'RingSuccessResponse',
)]
final class JsonSuccessViewModel extends JsonPreferenceListViewModel
{
    public function __construct(#[Ignore] private readonly PreferenceList $preferenceList)
    {
        parent::__construct(Response::HTTP_OK);
    }

    /**
     * @return array<JsonPreferenceEntryResponse>
     */
    #[OA\Property(
        description: 'For each ring slot, its virtual node',
    )]
    public function getEntries(): array
    {
        return array_values(array_map(fn (PreferenceEntry $slot) => $this->convertEntryForResponse($slot), $this->preferenceList->getEntries()));
    }

    private function convertEntryForResponse(PreferenceEntry $preferenceEntry): JsonPreferenceEntryResponse
    {
        return new JsonPreferenceEntryResponse(
            $preferenceEntry->getSlot(),
            $preferenceEntry->getOwnerId()->toRfc4122(),
            array_map(fn (UuidV7 $uuidV7) => $uuidV7->toRfc4122(), $preferenceEntry->getCoordinatorsIds()),
            array_map(fn (UuidV7 $uuidV7) => $uuidV7->toRfc4122(), $preferenceEntry->getOthersNodesIds()),
        );
    }
}
