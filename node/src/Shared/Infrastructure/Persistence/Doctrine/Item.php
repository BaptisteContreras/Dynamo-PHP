<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Model\Versioning\VectorClock;
use App\Shared\Infrastructure\Persistence\Type\VectorClockJson;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV7;

/**
 * There is only one active row for (key + version): key + version + active=true -> data
 * when an Item is added in this table, we must check if a (key + version) already exists. If so, we mark it as "not active"
 * and insert the new one.
 *
 * This update + insert seems safer and more efficient than delete + insert.
 *
 * Depending on the amount of write we may need to change this strategy...
 */
#[Entity]
#[UniqueConstraint(fields: ['key', 'version'], options: ['where' => 'active IS true'])]
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
        #[Column(type: Types::BOOLEAN)] private bool $active,
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

    public function isActive(): bool
    {
        return $this->active;
    }
}
