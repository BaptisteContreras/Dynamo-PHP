<?php

namespace App\Foreground\Domain\Service;

use App\Foreground\Domain\Exception\CannotForwardWriteException;
use App\Foreground\Domain\Model\Aggregate\Node\Node;
use App\Foreground\Domain\Model\Aggregate\Put\Item;
use App\Foreground\Domain\Model\Const\ForwardResult;
use App\Foreground\Domain\Out\Node\FinderInterface as NodeFinder;
use App\Foreground\Domain\Out\PreferenceList\FinderInterface as PreferenceListFinder;
use App\Foreground\Domain\Service\Forward\ForwardStrategyInterface;
use App\Shared\Domain\Event\EventBusInterface;
use App\Shared\Domain\Event\Sync\TechnicalErrorNodeEvent;
use App\Shared\Domain\Event\Sync\UnresponsiveNodeEvent;

readonly class Coordinator implements PutCoordinatorInterface
{
    public function __construct(
        private PreferenceListFinder $preferenceListFinder,
        private NodeFinder $nodeFinder,
        private ForwardStrategyInterface $forwardStrategy,
        private EventBusInterface $eventBus,
    ) {
    }

    public function isLocalNodeOwnerOf(Item $item): bool
    {
        $preferenceList = $this->preferenceListFinder->getPreferenceList();
        $localNode = $this->nodeFinder->getLocalEntry();

        $entryForSlot = $preferenceList->getEntry($item->getRingKey());

        return $entryForSlot->getOwnerId()->equals($localNode->getId());
    }

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
                // TODO handle write locally

                throw new \Exception('Not implemented');
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
