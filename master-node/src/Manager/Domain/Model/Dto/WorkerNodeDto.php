<?php

namespace App\Manager\Domain\Model\Dto;

use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Model\Entity\LabelSlot;
use App\Manager\Domain\Model\Entity\WorkerNode;

class WorkerNodeDto
{
    /**
     * @param array<LabelSlotDto> $labelSlots
     */
    public function __construct(
        private readonly int $id,
        private readonly string $networkAddress,
        private readonly int $networkPort,
        private readonly WorkerState $workerState,
        private readonly \DateTimeImmutable $joinedAt,
        private readonly string $labelName,
        private readonly int $weight,
        private readonly array $labelSlots
    ) {
    }

    public static function fromEntity(WorkerNode $wn): self
    {
        return new self(
            $wn->getId(),
            $wn->getNetworkAddress(),
            $wn->getNetworkPort(),
            $wn->getWorkerState(),
            $wn->getJoinedAt(),
            $wn->getLabelName(),
            $wn->getWeight(),
            array_map(function (LabelSlot $labelSlot) {
                return LabelSlotDto::fromEntity($labelSlot);
            }, $wn->getLabelSlots())
        );
    }

    public function getId(): int
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

    public function getWorkerState(): WorkerState
    {
        return $this->workerState;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function getLabelName(): string
    {
        return $this->labelName;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return array<LabelSlotDto>
     */
    public function getLabelSlots(): array
    {
        return $this->labelSlots;
    }
}
