<?php

namespace App\Manager\Application\Command\Worker\Register\Response;

use App\Manager\Domain\Exception\DomainException;

class ErrorResponse extends RegisterWorkerNodeResponse
{
    public function __construct(private readonly DomainException $domainException)
    {
    }

    public function getError(): DomainException
    {
        return $this->domainException;
    }
}
