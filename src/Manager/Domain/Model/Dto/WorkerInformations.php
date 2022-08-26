<?php

namespace App\Manager\Domain\Model\Dto;

use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Contract\DtoV1Interface;

class WorkerInformations implements DtoV1Interface
{
    /**         Properties         **/
    private int $id;

    private string $networkAddress;

    private int $networkPort;

    private WorkerState $workerState;

    private \DateTimeImmutable $joinedAt;

    private WorkerPreferenceList $workerPreferenceList;

    private string $labelName;

    /**
     * @var array<Label>
     */
    private array $subLabels;

    private int $weight;

    /**         Constructor         **/
    /**         Methods         **/
    public function jsonSerialize(): mixed
    {
        // TODO: Implement jsonSerialize() method.
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

    public function getWorkerPreferenceList(): WorkerPreferenceList
    {
        return $this->workerPreferenceList;
    }

    public function setWorkerPreferenceList(WorkerPreferenceList $workerPreferenceList): void
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
}
