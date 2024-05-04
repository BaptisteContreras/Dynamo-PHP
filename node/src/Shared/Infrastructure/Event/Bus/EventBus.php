<?php

namespace App\Shared\Infrastructure\Event\Bus;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class EventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->messageBus->dispatch($event);
        }
    }
}
