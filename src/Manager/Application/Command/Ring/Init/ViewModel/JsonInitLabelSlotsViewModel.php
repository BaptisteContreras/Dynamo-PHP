<?php

namespace App\Manager\Application\Command\Ring\Init\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class JsonInitLabelSlotsViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function validationError(ConstraintViolationListInterface $validationErrors): self
    {
        return new JsonValidationErrorViewModel($validationErrors);
    }

    public static function success(): self
    {
        return new JsonSuccessViewModel();
    }

    public static function error(DomainException $domainException): self
    {
        return new JsonErrorViewModel($domainException);
    }
}
