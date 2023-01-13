<?php

namespace App\Manager\Domain\Service;

use App\Manager\Domain\Contract\Out\Finder\LabelFinder;
use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;
use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Manager\Domain\Service\Label\LabelAssignationStrategyInterface;
use App\Manager\Domain\Service\Label\LabelNameGeneratorInterface;
use Psr\Log\LoggerInterface;

class LabelSet
{
    public function __construct(
        private readonly LabelFinder $labelFinder,
        private readonly LabelAssignationStrategyInterface $labelAssignationStrategy,
        private readonly LabelNameGeneratorInterface $labelNameGenerator,
        private readonly LoggerInterface $logger
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
     */
    public function acquireLabels(WorkerNode $workerNode, int $numberOfLabel, bool $strictRequirement): void
    {
        $labelSlots = $this->labelAssignationStrategy->selectSlots($numberOfLabel, $strictRequirement);

        $labelName = $this->labelNameGenerator->generate();

        $this->logger->info(sprintf(
            '[LABEL SET] : %s is the label name for worker node %s %s',
            $labelName,
            $workerNode->getNetworkAddress(),
            $workerNode->getNetworkPort()
        ));

        $workerNode->setLabelName($labelName);

        if (($nbLabelSlotsFound = count($labelSlots)) !== $numberOfLabel) {
            $this->logger->warning(sprintf(
                '[LABEL SET] : could not find enough free label slots. Only %s instead of %s will be used for worker node %s %s',
                $nbLabelSlotsFound,
                $numberOfLabel,
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
        }
    }

    /**
     * Returns true if some label slots are still free.
     *
     * /!\ This method does not know anything about assignation and locking. It may return true even if the last
     * free label slots are locked and in a process of being bound /!\
     */
    public function hasFreeSlots(): bool
    {
        return count($this->labelFinder->findFree()) > 0;
    }
}
