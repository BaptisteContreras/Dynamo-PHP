<?php

namespace App\Manager\Domain\Exception;

class LabelsSlotsAlreadyInitializedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Labels slots have already been initialized');
    }
}
