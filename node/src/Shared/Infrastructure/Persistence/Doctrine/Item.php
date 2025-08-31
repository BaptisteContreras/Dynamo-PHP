<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Model\Versioning\VectorClock;
use App\Shared\Infrastructure\Persistence\Type\VectorClockJson;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

#[Entity]
class Item
{
    public function __construct(
        #[Id] #[Column(type: UuidType::NAME, unique: true)] private UuidV7 $id,
        #[Column(type: Types::TEXT)] private string $key,
        #[Column(type: Types::INTEGER)] private int $ringKey,
        #[Column(type: Types::TEXT)] private string $data,
        #[Column(type: VectorClockJson::TYPE)] private VectorClock $version,
        #[Column(type: Types::DATETIME_IMMUTABLE)] private \DateTimeImmutable $createdAt,
        #[Column(type: UuidType::NAME)] private UuidV7 $owner,
    ) {
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getRingKey(): int
    {
        return $this->ringKey;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getVersion(): VectorClock
    {
        return $this->version;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getOwner(): UuidV7
    {
        return $this->owner;
    }
}
