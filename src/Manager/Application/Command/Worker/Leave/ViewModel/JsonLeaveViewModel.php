<?php

namespace App\Manager\Application\Command\Worker\Leave\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class JsonLeaveViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function validationError(ConstraintViolationListInterface $validationErrors): self
    {
        return new JsonValidationErrorViewModel($validationErrors);
    }

    public static function error(DomainException $domainException): self
    {
        return new JsonErrorViewModel($domainException);
    }

    public static function success(): self
    {
        return new JsonSuccessViewModel();
    }

    public static function notFound(): self
    {
        return new JsonNotFoundViewModel();
    }

    #[Ignore]
    public function getCode(): HttpCode
    {
        return parent::getCode();
    }
}
