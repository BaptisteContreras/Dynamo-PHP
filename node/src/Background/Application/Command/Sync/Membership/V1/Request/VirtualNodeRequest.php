<?php

namespace App\Background\Application\Command\Sync\Membership\V1\Request;

use App\Background\Domain\Model\Aggregate\Ring\Node;
use App\Background\Domain\Model\Aggregate\Ring\VirtualNode;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\UuidV7;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Uuid;

final readonly class VirtualNodeRequest
{
    #[OA\Property(
        title: 'ID of the virtual node',
    )]
    #[Uuid]
    private string $id;

    #[OA\Property(
        title: 'label of the virtual node',
        type: 'string',
        example: 'A1',
    )]
    #[NotBlank]
    #[Length(min: 1, max: 255)]
    private string $label;

    #[OA\Property(
        title: 'Slot',
        example: '8',
    )]
    #[NotBlank]
    #[Positive]
    private int $slot;

    #[OA\Property(
        title: 'Creation date of this virtual node',
    )]
    private \DateTimeImmutable $createdAt;

    #[OA\Property(
        title: 'Is this virtual node active',
    )]
    private bool $active;

    public function __construct(string $id, string $label, int $slot, \DateTimeImmutable $createdAt, bool $active)
    {
        $this->id = $id;
        $this->label = $label;
        $this->slot = $slot;
        $this->createdAt = $createdAt;
        $this->active = $active;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function asDto(Node $node): VirtualNode
    {
        return new VirtualNode(
            UuidV7::fromString($this->id),
            $this->label,
            $this->slot,
            $this->createdAt,
            $node,
            $this->active
        );
    }
}
