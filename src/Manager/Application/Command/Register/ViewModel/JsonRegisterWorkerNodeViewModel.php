<?php

namespace App\Manager\Application\Command\Register\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Manager\Domain\Model\Dto\WorkerNode;
use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class JsonRegisterWorkerNodeViewModel implements JsonViewModelInterface
{
    public function __construct(protected readonly HttpCode $httpCode)
    {
    }

    public static function validationError(ConstraintViolationListInterface $validationErrors): self
    {
        return new JsonRegisterWorkerNodeValidationErrorViewModel($validationErrors);
    }

    public static function error(DomainException $domainException): self
    {
        return new JsonRegisterWorkerNodeErrorViewModel($domainException);
    }

    public static function success(WorkerNode $workerNode): self
    {
        return new JsonRegisterWorkerNodeSuccessViewModel($workerNode);
    }

    public function getCode(): HttpCode
    {
        return $this->httpCode;
    }
}
