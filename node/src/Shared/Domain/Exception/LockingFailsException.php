<?php

namespace App\Shared\Domain\Exception;

class LockingFailsException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Cannot acquire a lock for this resource');
    }
}
