<?php

namespace App\Manager\Domain\Service;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;
use App\Manager\Domain\Contract\Out\Counter\LabelSlotCounterInterface;
use App\Manager\Domain\Contract\Out\Finder\LabelSlotFinderInterface;
use App\Manager\Domain\Contract\Out\Repository\LabelSlotRepositoryInterface;
use App\Manager\Domain\Contract\Out\Repository\WorkerNodeRepositoryInterface;
use App\Manager\Domain\Exception\LabelsSlotsAlreadyInitializedException;
use App\Manager\Domain\Exception\NoFreeLabelSlotFoundException;
use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;
use App\Manager\Domain\Exception\UnsupportedLabelSlotInitStrategyException;
use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Manager\Domain\Service\Label\Init\LabelSlotInitStrategyInterface;
use App\Manager\Domain\Service\Label\LabelSlotAssignationStrategyInterface;
use App\Manager\Domain\Service\Label\LabelSlotLockerInterface;
use Psr\Log\LoggerInterface;

class LabelSlotSet
{
    /**
     * @param iterable<LabelSlotInitStrategyInterface> $initStrategies
     */
    public function __construct(
        private readonly LabelSlotCounterInterface $labelSlotCounter,
        private readonly LabelSlotAssignationStrategyInterface $labelSlotAssignationStrategy,
        private readonly LoggerInterface $logger,
        private readonly LabelSlotLockerInterface $labelSlotLocker,
        private readonly WorkerNodeRepositoryInterface $workerNodeRepository,
        private readonly LabelSlotRepositoryInterface $labelSlotRepository,
        private readonly LabelSlotFinderInterface $labelSlotFinder,
        private readonly iterable $initStrategies
    ) {
    }

    /**
     * Try to acquire $numberOfLabel for the given worker node.
     * This method use a pessimistic lock for the labels returned by the assignation strategy to ensure that concurrency
     * register cannot steal labels from each other. The lock is released as soon as the transaction to write the labels
     * assignation to the worker node is finished (with success or not).
     * If there is at least one label available, the method succeed.
     *
     * If $strictRequirement is true, this method fails and throw an exception if there is not enough labels available.
     * $numberOfLabel MUST be positive or an exception is thrown
     *
     * @throws NotEnoughFreeLabelSlotException
     * @throws NoFreeLabelSlotFoundException
     */
    public function acquireSlots(WorkerNode $workerNode, int $numberOfSlots, bool $strictRequirement): void
    {
        $labelSlots = $this->labelSlotAssignationStrategy->selectSlots($numberOfSlots, $strictRequirement);
        $nbLabelSlotsFound = count($labelSlots);

        if (0 === $nbLabelSlotsFound) {
            throw new NoFreeLabelSlotFoundException();
        }

        if ($nbLabelSlotsFound !== $numberOfSlots) {
            $this->logger->warning(sprintf(
                '[LABEL SLOT SET][ACQUIRE] : could not find enough free label slots. Only %s instead of %s will be used for worker node %s %s',
                $nbLabelSlotsFound,
                $numberOfSlots,
                $workerNode->getNetworkAddress(),
                $workerNode->getNetworkPort()
            ));
        }

        foreach ($labelSlots as $labelSlot) {
            $this->logger->info(sprintf(
                '[LABEL SLOT SET][ACQUIRE] : give name %s to label %s',
                $workerNode->getLabelName(),
                $labelSlot->getId(),
            ));

            $this->logger->info(sprintf(
                '[LABEL SLOT SET][ACQUIRE] : bind label %s to worker node %s %s',
                $labelSlot->getId(),
                $workerNode->getNetworkAddress(),
                $workerNode->getNetworkPort()
            ));

            $labelSlot->setOwnership($workerNode);

            $this->labelSlotRepository->update($labelSlot, false);
        }

        $this->workerNodeRepository->update($workerNode, true);
        $this->labelSlotLocker->unlockSlotsForAssignation($labelSlots);
    }

    /**
     * Set free all the slots owned by the given worker node.
     */
    public function releaseSlots(WorkerNode $workerNode): void
    {
        foreach ($workerNode->getLabelSlots() as $labelSlot) {
            $this->logger->info(sprintf(
                '[LABEL FREE] : label %s is now free',
                $labelSlot->getId()
            ));

            $labelSlot->resetOwnership();

            $this->labelSlotRepository->update($labelSlot, false);
        }

        $this->labelSlotRepository->flushTransaction();
    }

    /**
     * Returns true if some label slots are still free.
     *
     * /!\ This method does not know anything about assignation and locking. It may return true even if the last
     * free label slots are locked and in a process of being bound /!\
     */
    public function hasFreeSlots(): bool
    {
        return $this->labelSlotCounter->countFree() > 0;
    }

    /**
     * Delete all label slots.
     *
     * /!\ Not concurrent safe /!\
     * /!\ Only for test purpose /!\
     */
    public function reset(): void
    {
        foreach ($this->labelSlotFinder->findAllSlots() as $labelSlot) {
            $labelSlot->resetOwnership();
            $this->labelSlotRepository->remove($labelSlot, false);
        }

        $this->labelSlotRepository->flushTransaction();
    }

    /**
     * Init the slots following the $allocationStrategy plan.
     * Returns the number of slots created.
     *
     * TODO -> lock for concurrency
     *
     * @throws LabelsSlotsAlreadyInitializedException
     * @throws UnsupportedLabelSlotInitStrategyException
     */
    public function init(LabelsSlotsAllocationStrategy $allocationStrategy): void
    {
        if ($this->labelSlotCounter->countAll() > 0) {
            throw new LabelsSlotsAlreadyInitializedException();
        }

        $this->selectInitStrategyImplementation($allocationStrategy)->createSlots();
    }

    /**
     * @throws UnsupportedLabelSlotInitStrategyException
     */
    private function selectInitStrategyImplementation(LabelsSlotsAllocationStrategy $allocationStrategy): LabelSlotInitStrategyInterface
    {
        /** @var LabelSlotInitStrategyInterface $initStrategy */
        foreach ($this->initStrategies as $initStrategy) {
            if ($initStrategy->supports($allocationStrategy)) {
                return $initStrategy;
            }
        }

        throw new UnsupportedLabelSlotInitStrategyException($allocationStrategy);
    }
}
