<?php

namespace App\Background\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

class CannotBuildPreferenceListException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Cannot build a preference list form the given ring');
    }
}
