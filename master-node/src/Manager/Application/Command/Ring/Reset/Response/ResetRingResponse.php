<?php

namespace App\Manager\Application\Command\Ring\Reset\Response;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Application\ApplicationResponseInterface;

abstract class ResetRingResponse implements ApplicationResponseInterface
{
    public static function withError(DomainException $domainException): self
    {
        return new ErrorResponse($domainException);
    }

    public static function success(): self
    {
        return new SuccessResponse();
    }
}
