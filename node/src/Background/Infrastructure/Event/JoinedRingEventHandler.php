<?php

namespace App\Background\Infrastructure\Event;

use App\Background\Application\Command\Local\Join\LocalNodeJoinCommandHandler;
use App\Shared\Domain\Event\EventHandlerInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;

class JoinedRingEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly LocalNodeJoinCommandHandler $localNodeJoinCommandHandler,
    ) {
    }

    public function __invoke(JoinedRingEvent $event): void
    {
        ($this->localNodeJoinCommandHandler)($event);
    }
}
