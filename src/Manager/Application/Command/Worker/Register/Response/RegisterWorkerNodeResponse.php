<?php

namespace App\Manager\Application\Command\Worker\Register\Response;

use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Model\Dto\WorkerNodeDto;
use App\Shared\Application\ApplicationResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class RegisterWorkerNodeResponse implements ApplicationResponseInterface
{
    public static function withValidationError(ConstraintViolationListInterface $validationError): self
    {
        return new ValidationErrorResponse($validationError);
    }

    public static function withError(DomainException $domainException): self
    {
        return new ErrorResponse($domainException);
    }

    public static function success(WorkerNodeDto $workerNode): self
    {
        return new SuccessResponse($workerNode);
    }
}
