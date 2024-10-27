<?php

namespace App\Foreground\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class CannotForwardWriteException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The write operation could not be handled by any node in the ring');
    }
}
