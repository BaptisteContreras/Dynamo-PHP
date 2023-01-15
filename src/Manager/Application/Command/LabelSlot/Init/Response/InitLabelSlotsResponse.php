<?php

namespace App\Manager\Application\Command\LabelSlot\Init\Response;

use App\Shared\Application\ApplicationResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class InitLabelSlotsResponse implements ApplicationResponseInterface
{
    public static function withValidationError(ConstraintViolationListInterface $validationError): self
    {
        return new ValidationErrorResponse($validationError);
    }
}
