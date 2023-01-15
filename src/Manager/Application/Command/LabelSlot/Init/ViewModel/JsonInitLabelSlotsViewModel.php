<?php

namespace App\Manager\Application\Command\LabelSlot\Init\ViewModel;

use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class JsonInitLabelSlotsViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function validationError(ConstraintViolationListInterface $validationErrors): self
    {
        return new JsonValidationErrorViewModel($validationErrors);
    }
}
