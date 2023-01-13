<?php

namespace App\Manager\Domain\Model\Dto;

class Label
{
    private int $id;

    private string $name; // Like label A, B, C, ...

    private int $subDivision; // Like A1, A2, A...,

    private float $position;

    private float $coverZoneExpension;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSubDivision(): int
    {
        return $this->subDivision;
    }

    public function setSubDivision(int $subDivision): void
    {
        $this->subDivision = $subDivision;
    }

    public function getPosition(): float
    {
        return $this->position;
    }

    public function setPosition(float $position): void
    {
        $this->position = $position;
    }

    public function getCoverZoneExpension(): float
    {
        return $this->coverZoneExpension;
    }

    public function setCoverZoneExpension(float $coverZoneExpension): void
    {
        $this->coverZoneExpension = $coverZoneExpension;
    }
}
