<?php

namespace App\Manager\Application\Command\Worker\Join\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Model\Dto\WorkerNodeDto;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Application\ViewModel;
use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class JsonJoinViewModel extends ViewModel implements JsonViewModelInterface
{
    public static function validationError(ConstraintViolationListInterface $validationErrors): self
    {
        return new JsonValidationErrorViewModel($validationErrors);
    }

    public static function error(DomainException $domainException): self
    {
        return new JsonErrorViewModel($domainException);
    }

    public static function success(WorkerNodeDto $workerNode): self
    {
        return new JsonSuccessViewModel($workerNode);
    }

    #[Ignore]
    public function getCode(): HttpCode
    {
        return parent::getCode();
    }
}
