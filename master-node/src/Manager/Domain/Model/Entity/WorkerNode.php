<?php

namespace App\Manager\Domain\Model\Entity;

use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Model\Dto\WorkerNodeDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * @var Collection<LabelSlot>
     */
    private Collection $labelSlots;

    private int $currentSlotKey = 0;

    private int $weight;

    public function __construct(string $networkAddress, int $networkPort, int $weight)
    {
        $this->networkAddress = $networkAddress;
        $this->networkPort = $networkPort;
        $this->weight = $weight;

        $this->joinedAt = new \DateTimeImmutable();
        $this->labelSlots = new ArrayCollection();
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

    /**
     * @return array<LabelSlot>
     */
    public function getLabelSlots(): array
    {
        return $this->labelSlots->toArray();
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

    public function addLabelSlot(LabelSlot $label): self
    {
        if (!$this->labelSlots->contains($label)) {
            $this->labelSlots->add($label);
        }

        return $this;
    }

    public function removeLabelSlot(LabelSlot $label): self
    {
        $this->labelSlots->removeElement($label);

        return $this;
    }

    public function getCurrentLabelSlotKey(): int
    {
        $this->currentSlotKey = $this->currentSlotKey ?: (int) $this->labelSlots->key();

        return ++$this->currentSlotKey;
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

    public function markAsLeaving(): self
    {
        $this->workerState = WorkerState::LEAVING;

        return $this;
    }

    public function markAsJoiningError(): self
    {
        $this->workerState = WorkerState::JOINING_ERROR;

        return $this;
    }

    public function readOnly(): WorkerNodeDto
    {
        return WorkerNodeDto::fromEntity($this);
    }
}
