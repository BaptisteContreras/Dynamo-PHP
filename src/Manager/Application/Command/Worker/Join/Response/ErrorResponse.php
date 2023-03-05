<?php

namespace App\Manager\Application\Command\Worker\Join\Response;

use App\Manager\Domain\Exception\DomainException;

class ErrorResponse extends JoinResponse
{
    public function __construct(private readonly DomainException $domainException)
    {
    }

    public function getError(): DomainException
    {
        return $this->domainException;
    }
}
