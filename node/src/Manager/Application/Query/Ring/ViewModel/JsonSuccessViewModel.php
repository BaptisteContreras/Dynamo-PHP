<?php

namespace App\Manager\Application\Query\Ring\ViewModel;

use App\Manager\Domain\Model\Aggregate\Ring\Ring;
use Manager\Application\Query\Ring\ViewModel\Dto\JsonSlot;
use Manager\Domain\Model\Aggregate\Ring\Slot;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Schema(
    title: 'RingSuccessResponse',
)]
class JsonSuccessViewModel extends JsonRingViewModel
{
    public function __construct(private readonly Ring $ring)
    {
        parent::__construct(Response::HTTP_OK);
    }

    /**
     * @return array<JsonSlot>
     */
    #[OA\Property(
        description: 'For each ring slot, its virtual node',
    )]
    public function getRing(): array
    {
        return array_map(fn (Slot $slot) => $this->convertSlotForResponse($slot), $this->ring->getSlots());
    }

    private function convertSlotForResponse(Slot $slot): JsonSlot
    {
        return new JsonSlot(
            $slot->getSlot(),
            $slot->getVirtualNode()->getId()
        );
    }
}
