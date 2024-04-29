<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Const\NodeState;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[Entity]
class Node
{
    public function __construct(
        #[Column(type: Types::STRING, length: 15)] private string $networkAddress,
        #[Column(type: Types::INTEGER)] private int $networkPort,
        #[Column(type: Types::SMALLINT, enumType: NodeState::class)] private NodeState $state,
        #[Column(type: Types::DATETIME_IMMUTABLE)] private \DateTimeImmutable $joinedAt,
        #[Column(type: Types::SMALLINT)] private int $weight,
        #[Column(type: Types::BOOLEAN)] private bool $selfEntry,
        #[Id] #[Column(type: UuidType::NAME, unique: true)] private UuidV7 $id = new UuidV7()
    ) {
    }

    public function getId(): ?UuidV7
    {
        return $this->id;
    }

    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function getState(): NodeState
    {
        return $this->state;
    }

    public function setState(NodeState $state): void
    {
        $this->state = $state;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function isSelfEntry(): bool
    {
        return $this->selfEntry;
    }
}
