<?php

namespace App\Manager\Domain\Model\Dto;

use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Model\Entity\LabelSlot;
use App\Manager\Domain\Model\Entity\PreferenceList;
use App\Manager\Domain\Model\Entity\WorkerNode;

class WorkerNodeDto
{
    private int $id;

    private string $networkAddress;

    private int $networkPort;

    private WorkerState $workerState;

    private \DateTimeImmutable $joinedAt;

    private PreferenceList $workerPreferenceList;

    private string $labelName;

    /**
     * @var array<LabelSlot>
     */
    private array $subLabels;

    private int $weight;

    public function __construct(int $id, string $networkAddress, int $networkPort, WorkerState $workerState, \DateTimeImmutable $joinedAt, string $labelName, int $weight)
    {
        $this->id = $id;
        $this->networkAddress = $networkAddress;
        $this->networkPort = $networkPort;
        $this->workerState = $workerState;
        $this->joinedAt = $joinedAt;
        $this->labelName = $labelName;
        $this->weight = $weight;
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
            $wn->getWeight()
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

    public function getWorkerPreferenceList(): PreferenceList
    {
        return $this->workerPreferenceList;
    }

    public function getLabelName(): string
    {
        return $this->labelName;
    }

    public function getSubLabels(): array
    {
        return $this->subLabels;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}
