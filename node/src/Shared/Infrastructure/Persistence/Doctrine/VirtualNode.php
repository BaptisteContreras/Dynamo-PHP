<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[Entity]
class VirtualNode
{
    public function __construct(
        #[Column(type: Types::STRING, length: 255)] private string $subLabel,
        #[Column(type: Types::INTEGER)] private int $slot,
        #[ManyToOne(targetEntity: Node::class, inversedBy: 'virtualNodes')] #[JoinColumn(nullable: false)] private Node $node,
        #[Column(type: Types::DATETIME_IMMUTABLE)] private \DateTimeImmutable $createdAt,
        #[Column(type: Types::BOOLEAN)] private bool $active,
        #[Id] #[Column(type: UuidType::NAME, unique: true)] private UuidV7 $id = new UuidV7(),
    ) {
    }

    public function getSubLabel(): string
    {
        return $this->subLabel;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
