<?php

namespace App\Manager\Application\Command\Ring\Reset\Response;

use App\Manager\Domain\Exception\DomainException;

class ErrorResponse extends ResetRingResponse
{
    public function __construct(private readonly DomainException $domainException)
    {
    }

    public function getError(): DomainException
    {
        return $this->domainException;
    }
}
