<?php

namespace App\Manager\Application\Command\Register\Response;

use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Shared\Application\ApplicationResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class RegisterWorkerNodeResponse implements ApplicationResponseInterface
{
    public static function withValidationError(ConstraintViolationListInterface $validationError): self
    {
        return new RegisterWorkerNodeValidationErrorResponse($validationError);
    }

    public static function withError(DomainException $domainException): self
    {
        return new RegisterWorkerNodeErrorResponse($domainException);
    }

    public static function success(WorkerNode $workerNode): self
    {
        return new RegisterWorkerNodeSuccessResponse($workerNode);
    }
}
