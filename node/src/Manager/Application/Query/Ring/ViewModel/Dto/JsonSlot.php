<?php

namespace Manager\Application\Query\Ring\ViewModel\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'RingSlot',
)]
final readonly class JsonSlot
{
    public function __construct(
        private int $slot,
        private string $virtualNodeId
    ) {
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    #[OA\Property(
        description: 'The unique UID of the virtual node in the ring',
        example: 'abcf3554-c788-7a58-a441-89421dabad90'
    )]
    public function getVirtualNodeId(): string
    {
        return $this->virtualNodeId;
    }
}
