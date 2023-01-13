<?php

namespace App\Manager\Domain\Exception;

class WorkerAlreadyRegisteredException extends \Exception
{
    public function __construct(string $networkAddress, int $networkPort)
    {
        parent::__construct(sprintf(
            'A worker with the IP %s and PORT %s is already register in the ring',
            $networkAddress,
            $networkPort
        ));
    }
}
