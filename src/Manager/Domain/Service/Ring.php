<?php

namespace App\Manager\Domain\Service;

use App\Manager\Domain\Constante\Enum\LabelsSlotsAllocationStrategy;
use App\Manager\Domain\Constante\Enum\WorkerState;
use App\Manager\Domain\Contract\Out\Finder\WorkerNodeFinderInterface;
use App\Manager\Domain\Contract\Out\Repository\WorkerNodeRepositoryInterface;
use App\Manager\Domain\Exception\AlreadyLockException;
use App\Manager\Domain\Exception\LabelsSlotsAlreadyInitializedException;
use App\Manager\Domain\Exception\LockingFailsException;
use App\Manager\Domain\Exception\NoFreeLabelSlotFoundException;
use App\Manager\Domain\Exception\NotEnoughFreeLabelSlotException;
use App\Manager\Domain\Exception\RingFullException;
use App\Manager\Domain\Exception\UnsupportedLabelSlotInitStrategyException;
use App\Manager\Domain\Exception\WorkerAlreadyJoinedException;
use App\Manager\Domain\Exception\WrongWorkerStateException;
use App\Manager\Domain\Model\Entity\WorkerNode;
use App\Manager\Domain\Service\Label\LabelNameGeneratorInterface;
use App\Manager\Domain\Service\Worker\WorkerNodeLockerInterface;
use Psr\Log\LoggerInterface;

class Ring
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly LabelSlotSet $labelSet,
        private readonly WorkerNodeLockerInterface $workerNodeLocker,
        private readonly WorkerNodeFinderInterface $workerNodeFinder,
        private readonly WorkerNodeRepositoryInterface $workerNodeRepository,
        private readonly LabelNameGeneratorInterface $labelNameGenerator
    ) {
    }

    /**
     * Try to make the given worker node join the ring.
     * During this method, a lock is set and no other worker node with the same network address and network port can try
     * to join the pool. As it is a pessimist lock, it throws an exception without waiting.
     *
     * ########################################## Conditions to join the ring ##########################################
     *  - No other worker node with the same network address and network port exists in the ring
     *  - The ring is not full (i.e. the maximum size of the ring depends on the number of labels) -> for the moment
     * the maximum size of the ring is fixed to 360
     *  - The worker node must be in the "joining" state
     *
     * @throws RingFullException
     * @throws LockingFailsException
     * @throws WorkerAlreadyJoinedException|WrongWorkerStateException|NoFreeLabelSlotFoundException
     * @throws AlreadyLockException
     */
    public function join(WorkerNode $workerNode): void
    {
        if (!$workerNode->isJoining()) {
            throw new WrongWorkerStateException($workerNode->getWorkerState());
        }

        if ($this->isFull()) {
            throw new RingFullException();
        }

        $this->workerNodeLocker->lockWorkerNodeForJoining($workerNode);

        $alreadyExistingWorker = $this->workerNodeFinder->findOneByIpAndPort(
            $workerNode->getNetworkAddress(),
            $workerNode->getNetworkPort()
        );

        if ($alreadyExistingWorker) {
            throw new WorkerAlreadyJoinedException($workerNode->getNetworkAddress(), $workerNode->getNetworkPort());
        }

        $labelName = $this->labelNameGenerator->generate();

        $this->logger->info(sprintf(
            '[RING][JOIN] : %s is the label name for worker node %s %s',
            $labelName,
            $workerNode->getNetworkAddress(),
            $workerNode->getNetworkPort()
        ));

        $workerNode->setLabelName($labelName);

        $this->workerNodeRepository->add($workerNode, true);

        try {
            $this->labelSet->acquireSlots($workerNode, $workerNode->getWeight(), true);

            $workerNode->markAsUp();
            $this->workerNodeRepository->update($workerNode, true);
        } catch (NotEnoughFreeLabelSlotException|NoFreeLabelSlotFoundException $exception) {
            $workerNode->markAsJoiningError();
            $this->logger->error(sprintf(
                '[RING][JOIN] : %s cannot join the ring : %s',
                $workerNode->getId(),
                $exception->getMessage()
            ));

            $this->leave($workerNode);

            throw new RingFullException();
        } catch (\Throwable $exception) {
            $workerNode->markAsJoiningError();
            $this->logger->critical(sprintf(
                '[RING][JOIN] : %s failed with fatal exception %s',
                $workerNode->getId(),
                $exception->getMessage()
            ));

            $this->leave($workerNode);

            throw $exception;
        } finally {
            $this->workerNodeLocker->unlockWorkerNodeForJoining($workerNode);
        }
    }

    /**
     * Make the given worker node leave the ring.
     *
     * A worker in joining or leaving state cannot leave the ring.
     *
     * @throws AlreadyLockException
     * @throws LockingFailsException
     * @throws WrongWorkerStateException
     */
    public function leave(WorkerNode $workerNode): void
    {
        $this->checkLeavingWorkerNodeStatus($workerNode);

        $this->leaveWithoutCheck($workerNode);
    }

    /**
     * Make all worker node leave and delete all label slots.
     *
     * /!\ Concurrent joining worker node cannot be deleted /!\
     * /!\ Only for test purpose /!\
     */
    public function reset(): void
    {
        foreach ($this->workerNodeFinder->findAll() as $workerNode) {
            try {
                $this->leaveWithoutCheck($workerNode);
            } catch (AlreadyLockException|LockingFailsException) {
                // @ignoreException
            }
        }

        $this->workerNodeRepository->flushTransaction();

        $this->labelSet->reset();
    }

    /**
     * /!\ Locked futures resources are not taking into account for the calculation /!\.
     */
    public function isFull(): bool
    {
        return !$this->labelSet->hasFreeSlots();
    }

    /**
     * @throws LabelsSlotsAlreadyInitializedException
     * @throws UnsupportedLabelSlotInitStrategyException
     */
    public function initLabelsSlots(LabelsSlotsAllocationStrategy $allocationStrategy): void
    {
        $this->labelNameGenerator->initSequence(); // todo move in init ring method
        $this->labelSet->init($allocationStrategy);
    }

    /**
     * @return array<WorkerNode>
     */
    public function getWorkers(): array
    {
        return $this->workerNodeFinder->findAll();
    }

    /**
     * @throws WrongWorkerStateException
     */
    private function checkLeavingWorkerNodeStatus(WorkerNode $workerNode): void
    {
        if (in_array($workerNode->getWorkerState(), [
            WorkerState::JOINING,
            WorkerState::LEAVING,
            ], true)) {
            throw new WrongWorkerStateException($workerNode->getWorkerState());
        }
    }

    /**
     * @throws LockingFailsException
     * @throws AlreadyLockException
     */
    private function leaveWithoutCheck(WorkerNode $workerNode): void
    {
        $this->workerNodeLocker->lockWorkerNodeForLeaving($workerNode);

        $workerNode->markAsLeaving();

        $this->workerNodeRepository->update($workerNode, true);

        $this->labelSet->releaseSlots($workerNode);

        $this->workerNodeRepository->remove($workerNode, true);

        $this->workerNodeLocker->unlockWorkerNodeForLeaving($workerNode);
    }
}
