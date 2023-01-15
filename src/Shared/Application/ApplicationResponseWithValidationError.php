<?php

namespace App\Shared\Application;

use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ApplicationResponseWithValidationError
{
    public function __construct(private readonly ConstraintViolationListInterface $validationErrors)
    {
    }

    public function getValidationErrors(): ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }
}
