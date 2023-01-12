<?php

namespace App\Manager\Application\Command\Register\Response;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class RegisterWorkerNodeErrorResponse extends RegisterWorkerNodeResponse
{
    public function __construct(private readonly ConstraintViolationListInterface $validationErrors)
    {
    }

    public function getValidationErrors(): ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }
}
