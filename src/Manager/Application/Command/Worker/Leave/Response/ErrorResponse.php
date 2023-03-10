<?php

namespace App\Manager\Application\Command\Worker\Leave\Response;

use App\Manager\Domain\Exception\DomainException;

class ErrorResponse extends LeaveResponse
{
    public function __construct(private readonly DomainException $domainException)
    {
    }

    public function getError(): DomainException
    {
        return $this->domainException;
    }
}
