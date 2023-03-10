<?php

namespace App\Manager\Domain\Exception;

use App\Manager\Domain\Constante\Enum\WorkerState;

class WrongWorkerStateException extends DomainException
{
    public function __construct(WorkerState $actual)
    {
        parent::__construct(sprintf(
            '%s is not a valid state for this action.',
            $actual->value
        ));
    }
}
