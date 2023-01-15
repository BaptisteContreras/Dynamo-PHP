<?php

namespace App\Manager\Domain\Model\Entity;

use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Model\Dto\WorkerNodeDto;

class WorkerNode
{
    private int $id;

    private string $networkAddress;

    private int $networkPort;

    private WorkerState $workerState = WorkerState::JOINING;

    private \DateTimeImmutable $joinedAt;

    private PreferenceList $workerPreferenceList;

    private string $labelName = '';

    /**
     * @var array<LabelSlot>
     */
    private array $subLabels = [];

    private int $weight;

    public function __construct(string $networkAddress, int $networkPort, int $weight)
    {
        $this->networkAddress = $networkAddress;
        $this->networkPort = $networkPort;
        $this->weight = $weight;

        $this->joinedAt = new \DateTimeImmutable();
        $this->workerPreferenceList = PreferenceList::emptyList();
    }

    /**         Accessors         **/
    public function getNetworkAddress(): string
    {
        return $this->networkAddress;
    }

    public function setNetworkAddress(string $networkAddress): void
    {
        $this->networkAddress = $networkAddress;
    }

    public function getNetworkPort(): int
    {
        return $this->networkPort;
    }

    public function setNetworkPort(int $networkPort): void
    {
        $this->networkPort = $networkPort;
    }

    public function getWorkerState(): WorkerState
    {
        return $this->workerState;
    }

    public function setWorkerState(WorkerState $workerState): void
    {
        $this->workerState = $workerState;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeImmutable $joinedAt): void
    {
        $this->joinedAt = $joinedAt;
    }

    public function getWorkerPreferenceList(): PreferenceList
    {
        return $this->workerPreferenceList;
    }

    public function setWorkerPreferenceList(PreferenceList $workerPreferenceList): void
    {
        $this->workerPreferenceList = $workerPreferenceList;
    }

    public function getLabelName(): string
    {
        return $this->labelName;
    }

    public function setLabelName(string $labelName): void
    {
        $this->labelName = $labelName;
    }

    public function getSubLabels(): array
    {
        return $this->subLabels;
    }

    public function setSubLabels(array $subLabels): void
    {
        $this->subLabels = $subLabels;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function addLabel(LabelSlot $label): self
    {
        $this->subLabels[] = $label;

        return $this;
    }

    public function isJoining(): bool
    {
        return WorkerState::JOINING === $this->workerState;
    }

    public function markAsUp(): self
    {
        $this->workerState = WorkerState::UP;

        return $this;
    }

    public function readOnly(): WorkerNodeDto
    {
        return WorkerNodeDto::fromEntity($this);
    }
}
