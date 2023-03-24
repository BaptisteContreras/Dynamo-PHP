<?php

namespace App\Manager\Domain\Exception;

class NoFreeLabelSlotFoundException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Cannot find any free label slot.');
    }
}
