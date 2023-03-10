<?php

namespace App\Manager\Domain\Exception;

class LabelNameGeneratorSequenceInitException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The label name generator sequence is not initialized');
    }
}
