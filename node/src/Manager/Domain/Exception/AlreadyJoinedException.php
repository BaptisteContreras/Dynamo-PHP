<?php

namespace App\Manager\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class AlreadyJoinedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('This node has already joined a ring');
    }
}
