<?php

namespace App\Shared\Domain\Exception;

class AlreadyLockException extends DomainException
{
    public function __construct()
    {
        parent::__construct('This resource is already locked.');
    }
}
