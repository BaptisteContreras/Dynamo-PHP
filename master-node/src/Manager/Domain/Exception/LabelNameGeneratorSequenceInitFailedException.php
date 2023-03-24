<?php

namespace App\Manager\Domain\Exception;

class LabelNameGeneratorSequenceInitFailedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('The label name generator sequence initialization failed.');
    }
}
