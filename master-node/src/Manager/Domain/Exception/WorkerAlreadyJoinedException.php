<?php

namespace App\Manager\Domain\Exception;

class WorkerAlreadyJoinedException extends DomainException
{
    public function __construct(string $networkAddress, int $networkPort)
    {
        parent::__construct(sprintf(
            'A worker with the IP %s and PORT %s is already registered in the ring',
            $networkAddress,
            $networkPort
        ));
    }
}
