<?php

namespace App\Manager\Application\Command\Register\Response;

use App\Shared\Application\ApplicationResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class RegisterWorkerNodeResponse implements ApplicationResponseInterface
{
    public static function withValidationError(ConstraintViolationListInterface $validationError): self
    {
        return new RegisterWorkerNodeValidationErrorResponse($validationError);
    }
}
