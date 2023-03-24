<?php

namespace App\Manager\Application\Command\Ring\Init\Response;

use App\Manager\Domain\Exception\DomainException;

class ErrorResponse extends InitLabelSlotsResponse
{
    public function __construct(private readonly DomainException $domainException)
    {
    }

    public function getError(): DomainException
    {
        return $this->domainException;
    }
}
