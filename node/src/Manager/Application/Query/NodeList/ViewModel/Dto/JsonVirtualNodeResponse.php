<?php

namespace App\Manager\Application\Query\NodeList\ViewModel\Dto;

use OpenApi\Attributes as OA;

final readonly class JsonVirtualNodeResponse
{
    public function __construct(
        private string $id,
        private string $subLabel,
        private int $slot,
        private bool $active,
        private \DateTimeImmutable $createdAt,
    ) {
    }

    #[OA\Property(
        example: 'kdef3554-c788-7a58-a441-89421dabad90'
    )]
    public function getId(): string
    {
        return $this->id;
    }

    #[OA\Property(
        example: 'B5'
    )]
    public function getSubLabel(): string
    {
        return $this->subLabel;
    }

    #[OA\Property(
        example: 29
    )]
    public function getSlot(): int
    {
        return $this->slot;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
