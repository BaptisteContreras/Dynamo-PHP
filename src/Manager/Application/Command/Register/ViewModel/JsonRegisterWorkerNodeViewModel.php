<?php

namespace App\Manager\Application\Command\Register\ViewModel;

use App\Shared\Application\JsonViewModelInterface;
use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class JsonRegisterWorkerNodeViewModel implements JsonViewModelInterface
{
    public function __construct(protected readonly HttpCode $httpCode)
    {
    }

    public static function error(ConstraintViolationListInterface $validationErrors): self
    {
        return new JsonRegisterWorkerNodeErrorViewModel($validationErrors);
    }

    public function getCode(): HttpCode
    {
        return $this->httpCode;
    }
}
