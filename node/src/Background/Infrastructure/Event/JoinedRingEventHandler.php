<?php

namespace App\Background\Infrastructure\Event;

use App\Shared\Domain\Event\EventHandlerInterface;
use App\Shared\Domain\Event\Sync\JoinedRingEvent;

class JoinedRingEventHandler implements EventHandlerInterface
{
    public function __invoke(JoinedRingEvent $event): void
    {
    }
}
