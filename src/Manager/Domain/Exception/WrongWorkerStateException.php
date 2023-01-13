<?php

namespace App\Manager\Domain\Exception;

use App\Manager\Domain\Constante\Enum\WorkerState;

class WrongWorkerStateException extends \Exception
{
    public function __construct(WorkerState $expected, WorkerState $actual)
    {
        parent::__construct(sprintf(
            'The worker is not in the expected state %s. %s instead',
            $expected->value,
            $actual->value
        ));
    }
}
