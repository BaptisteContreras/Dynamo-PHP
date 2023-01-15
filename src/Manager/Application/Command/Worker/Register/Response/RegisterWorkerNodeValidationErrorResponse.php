<?php

namespace App\Manager\Application\Command\Worker\Register\Response;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class RegisterWorkerNodeValidationErrorResponse extends RegisterWorkerNodeResponse
{
    public function __construct(private readonly ConstraintViolationListInterface $validationErrors)
    {
    }

    public function getValidationErrors(): ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }
}
