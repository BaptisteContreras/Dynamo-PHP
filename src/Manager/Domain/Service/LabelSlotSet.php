<?php

namespace App\Manager\Domain\Service;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;
use App\Manager\Domain\Contract\Out\Counter\LabelCounter;
use App\Manager\Domain\Contract\Out\Repository\LabelRepositoryInterface;
use App\Manager\Domain\Contract\Out\Repository\WorkerNodeRepositoryInterface;
use App\Manager\Domain\Exception\LabelsSlotsAlreadyInitializedException;
use App\Manager\Domain\Exception\NoFreeLabelSlotFoundException;
use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;
use App\Manager\Domain\Exception\UnsupportedLabelSlotInitStrategyException;
use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Manager\Domain\Service\Label\Init\LabelSlotInitStrategyInterface;
use App\Manager\Domain\Service\Label\LabelAssignationStrategyInterface;
use App\Manager\Domain\Service\Label\LabelLockerInterface;
use App\Manager\Domain\Service\Label\LabelNameGeneratorInterface;
use Psr\Log\LoggerInterface;

class LabelSlotSet
{
    public function __construct(
        private readonly LabelCounter $labelCounter,
        private readonly LabelAssignationStrategyInterface $labelAssignationStrategy,
        private readonly LabelNameGeneratorInterface $labelNameGenerator,
        private readonly LoggerInterface $logger,
        private readonly LabelLockerInterface $labelLocker,
        private readonly WorkerNodeRepositoryInterface $workerNodeRepository,
        private readonly LabelRepositoryInterface $labelRepository,
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
        $labelSlots = $this->labelAssignationStrategy->selectSlots($numberOfSlots, $strictRequirement);
        $nbLabelSlotsFound = count($labelSlots);

        if (0 === $nbLabelSlotsFound) {
            throw new NoFreeLabelSlotFoundException();
        }

        $labelName = $this->labelNameGenerator->generate();

        $this->logger->info(sprintf(
            '[LABEL SET] : %s is the label name for worker node %s %s',
            $labelName,
            $workerNode->getNetworkAddress(),
            $workerNode->getNetworkPort()
        ));

        $workerNode->setLabelName($labelName);

        if ($nbLabelSlotsFound !== $numberOfSlots) {
            $this->logger->warning(sprintf(
                '[LABEL SET] : could not find enough free label slots. Only %s instead of %s will be used for worker node %s %s',
                $nbLabelSlotsFound,
                $numberOfSlots,
                $workerNode->getNetworkAddress(),
                $workerNode->getNetworkPort()
            ));
        }

        foreach ($labelSlots as $label) {
            $this->logger->info(sprintf(
                '[LABEL SET] : give name %s to label %s',
                $labelName,
                $label->getId(),
            ));

            $label->setName($labelName);

            $this->logger->info(sprintf(
                '[LABEL SET] : bind label %s to worker node %s %s',
                $label->getId(),
                $workerNode->getNetworkAddress(),
                $workerNode->getNetworkPort()
            ));

            $workerNode->addLabel($label);

            $this->labelRepository->update($label, false);
        }

        $this->workerNodeRepository->update($workerNode, true);
        $this->labelLocker->unlockSlotsForAssignation($labelSlots);
    }

    /**
     * Returns true if some label slots are still free.
     *
     * /!\ This method does not know anything about assignation and locking. It may return true even if the last
     * free label slots are locked and in a process of being bound /!\
     */
    public function hasFreeSlots(): bool
    {
        return $this->labelCounter->countFree() > 0;
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
        if ($this->labelCounter->countAll() > 0) {
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
