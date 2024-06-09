<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Const\HistoryEventType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[Entity]
class HistoryEvent
{
    public function __construct(
        #[Id] #[Column(type: UuidType::NAME, unique: true)] private UuidV7 $id,
        #[Column(type: UuidType::NAME)] private UuidV7 $node,
        #[Column(type: Types::INTEGER, enumType: HistoryEventType::class)] private HistoryEventType $type,
        #[Column(type: Types::DATETIME_IMMUTABLE)] private \DateTimeImmutable $eventTime,
        #[Column(type: Types::TEXT, nullable: true)] private ?string $data,
        #[Column(type: UuidType::NAME, nullable: true)] private ?UuidV7 $sourceNode,
        #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)] private ?\DateTimeImmutable $receivedAt
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getNode(): UuidV7
    {
        return $this->node;
    }

    public function getType(): HistoryEventType
    {
        return $this->type;
    }

    public function getSourceNode(): ?UuidV7
    {
        return $this->sourceNode;
    }

    public function getEventTime(): \DateTimeImmutable
    {
        return $this->eventTime;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }
}
