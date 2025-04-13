<?php

namespace App\Foreground\Domain\Exception;

class CannotForwardWriteException extends CannotHandleWriteException
{
    public function __construct()
    {
        parent::__construct('The write operation could not be handled by any node in the ring');
    }
}
