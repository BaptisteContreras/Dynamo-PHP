<?php

namespace App\Manager\Application\Command\Worker\Leave\Response;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Application\ApplicationResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class LeaveResponse implements ApplicationResponseInterface
{
    public static function withValidationError(ConstraintViolationListInterface $validationError): self
    {
        return new ValidationErrorResponse($validationError);
    }

    public static function withError(DomainException $domainException): self
    {
        return new ErrorResponse($domainException);
    }

    public static function success(): self
    {
        return new SuccessResponse();
    }

    public static function notFound(): self
    {
        return new NotFoundResponse();
    }
}
