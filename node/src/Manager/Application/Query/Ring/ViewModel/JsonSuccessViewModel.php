<?php

namespace App\Manager\Application\Query\Ring\ViewModel;

use App\Manager\Application\Query\Ring\ViewModel\Dto\JsonSlot;
use App\Manager\Application\Query\Ring\ViewModel\Dto\JsonVirtualNode;
use App\Manager\Domain\Model\Aggregate\Node\VirtualNode;
use App\Manager\Domain\Model\Aggregate\Ring\Ring;
use App\Manager\Domain\Model\Aggregate\Ring\Slot;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Schema(
    title: 'RingSuccessResponse',
)]
final class JsonSuccessViewModel extends JsonRingViewModel
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
        return array_values(array_map(fn (Slot $slot) => $this->convertSlotForResponse($slot), $this->ring->getSlots()));
    }

    /**
     * @return array<JsonVirtualNode>
     */
    #[OA\Property(
        description: 'A list of all virtual nodes and their node',
    )]
    public function getVirtualNodeList(): array
    {
        return array_values($this->ring->getVirtualNodes()->map($this->convertVirtualNodeForResponse(...)));
    }

    private function convertSlotForResponse(Slot $slot): JsonSlot
    {
        return new JsonSlot(
            $slot->getSlot(),
            $slot->getVirtualNode()->getId()
        );
    }

    private function convertVirtualNodeForResponse(VirtualNode $virtualNode): JsonVirtualNode
    {
        return new JsonVirtualNode($virtualNode->getStringId(), $virtualNode->getNodeStringId());
    }
}
