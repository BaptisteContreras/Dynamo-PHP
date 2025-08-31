<?php

namespace App\Foreground\Domain\Service\Forward;

use App\Foreground\Domain\Exception\CannotForwardWriteException;
use App\Foreground\Domain\Model\Aggregate\Item\Item;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Const\ForwardResult;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;
use App\Foreground\Domain\Service\Local\LocalCoordinator;
use App\Shared\Domain\Event\EventBusInterface;
use App\Shared\Domain\Event\Sync\TechnicalErrorNodeEvent;
use App\Shared\Domain\Event\Sync\UnresponsiveNodeEvent;

class Forwarder
{
    public function __construct(
        private readonly PreferenceListFinder $preferenceListFinder,
        private readonly NodeFinder $nodeFinder,
        private readonly ForwardStrategyInterface $forwardStrategy,
        private readonly EventBusInterface $eventBus,
        private readonly LocalCoordinator $localCoordinator,
    ) {
    }

    /**
     * @throws CannotForwardWriteException
     */
    public function forwardWrite(Item $item): Node
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();
        $entryForSlot = $preferenceList->getEntry($item->getRingKey());

        $slotCoordinatorsIds = $entryForSlot->getCoordinatorsPriorityList();
        $slotCoordinators = $this->nodeFinder->findByIds($slotCoordinatorsIds);

        foreach ($slotCoordinatorsIds as $slotCoordinatorId) {
            $slotCoordinator = $slotCoordinators[$slotCoordinatorId->toRfc4122()] ?? null;

            if (!$slotCoordinator) {
                continue;
            }

            if (!$slotCoordinator->canParticipateInRingOperation()) {
                continue;
            }

            if ($slotCoordinator->isSelfEntry()) {
                return $this->localCoordinator->handleWriteLocally($item);
            }

            if (ForwardResult::SUCCESS === ($forwardResult = $this->forwardStrategy->forwardItemWriteTo($slotCoordinator, $item))) {
                return $slotCoordinator;
            }

            $errorEvent = match ($forwardResult) {
                ForwardResult::TIMEOUT => UnresponsiveNodeEvent::unresponsiveWriteEvent($slotCoordinatorId),
                ForwardResult::TECHNICAL_ERROR => TechnicalErrorNodeEvent::unresponsiveWriteEvent($slotCoordinatorId),
            };

            $this->eventBus->publish($errorEvent);
        }

        throw new CannotForwardWriteException();
    }
}
