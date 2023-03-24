<?php

namespace App\Manager\Domain\Model\Dto;

use App\Manager\Domain\Model\Entity\LabelSlot;

class LabelSlotDto
{
    public function __construct(
        private readonly int $id,
        private readonly ?string $name,
        private readonly ?string $subDivision,
        private readonly float $position,
        private readonly float $coverZoneLength,
        private readonly ?int $owner
    ) {
    }

    public static function fromEntity(LabelSlot $ls): self
    {
        return new self(
            $ls->getId(),
            $ls->getName(),
            $ls->getSubDivision(),
            $ls->getPosition(),
            $ls->getCoverZoneLength(),
            $ls->getOwner()?->getId()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSubDivision(): ?string
    {
        return $this->subDivision;
    }

    public function getPosition(): float
    {
        return $this->position;
    }

    public function getCoverZoneLength(): float
    {
        return $this->coverZoneLength;
    }

    public function getOwner(): ?int
    {
        return $this->owner;
    }
}
