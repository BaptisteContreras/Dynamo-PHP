<?php

namespace App\Manager\Domain\Model\Entity;

class LabelSlot
{
    private int $id;

    private ?string $name = null; // Like label A, B, C, ...

    private ?string $subDivision = null; // Like A1, A2, A...,

    private float $position;

    private float $coverZoneLength;

    private ?WorkerNode $owner = null;

    public function __construct(float $position, float $coverZoneLength)
    {
        $this->position = $position;
        $this->coverZoneLength = $coverZoneLength;
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

    public function setPosition(float $position): void
    {
        $this->position = $position;
    }

    public function getCoverZoneLength(): float
    {
        return $this->coverZoneLength;
    }

    public function setCoverZoneLength(float $coverZoneLength): void
    {
        $this->coverZoneLength = $coverZoneLength;
    }

    public function getOwner(): ?WorkerNode
    {
        return $this->owner;
    }

    public function setOwnership(WorkerNode $workerNode): self
    {
        $this->owner = $workerNode;
        $workerNode->addLabelSlot($this);
        $this->name = $workerNode->getLabelName();
        $this->subDivision = sprintf('%s%s', $this->name, $workerNode->getCurrentLabelSlotKey());

        return $this;
    }

    public function resetOwnership(): self
    {
        if ($this->owner) {
            $this->owner->removeLabelSlot($this);
            $this->owner = null;
        }

        $this->name = null;
        $this->subDivision = null;

        return $this;
    }
}
