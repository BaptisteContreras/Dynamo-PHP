<?php

namespace App\Manager\Domain\Exception;

class RingFullException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The ring is full.');
    }
}
