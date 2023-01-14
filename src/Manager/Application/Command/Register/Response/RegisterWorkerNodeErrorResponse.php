<?php

namespace App\Manager\Application\Command\Register\Response;

use App\Manager\Domain\Exception\DomainException;

class RegisterWorkerNodeErrorResponse extends RegisterWorkerNodeResponse
{
    public function __construct(private readonly DomainException $domainException)
    {
    }

    public function getError(): DomainException
    {
        return $this->domainException;
    }
}
