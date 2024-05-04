<?php

namespace App\Shared\Domain\Event;

interface EventBusInterface
{
    public function publish(DomainEvent ...$events): void;
}
