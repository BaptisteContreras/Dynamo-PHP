<?php

namespace App\Manager\Application\Query\Ring\ViewModel\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(
    title: 'VirtualNodeResponse',
)]
final readonly class JsonVirtualNode
{
    public function __construct(
        private string $id,
        private string $nodeId
    ) {
    }

    #[OA\Property(
        description: 'The unique UID of the virtual node in the ring',
        example: 'abcf3554-c788-7a58-a441-89421dabad90'
    )]
    public function getId(): string
    {
        return $this->id;
    }

    #[OA\Property(
        description: 'The unique UID of the node owning this virtual node',
        example: '25cf3554-c788-7a58-a441-89421dabadfg'
    )]
    public function getNodeId(): string
    {
        return $this->nodeId;
    }
}
