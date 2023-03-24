<?php

namespace App\Manager\Domain\Exception;

class RingFullException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The ring is full.');
    }
}
