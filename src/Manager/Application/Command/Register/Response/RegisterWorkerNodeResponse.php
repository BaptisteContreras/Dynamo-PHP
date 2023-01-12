<?php

namespace App\Manager\Application\Command\Register\Response;

use App\Shared\Application\ApplicationResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class RegisterWorkerNodeResponse implements ApplicationResponseInterface
{
    public static function withError(ConstraintViolationListInterface $validationError): self
    {
        return new RegisterWorkerNodeErrorResponse($validationError);
    }
}
