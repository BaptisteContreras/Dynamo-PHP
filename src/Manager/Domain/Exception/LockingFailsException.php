<?php

namespace App\Manager\Domain\Exception;

class LockingFailsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Cannot acquire a lock for this resource');
    }
}
