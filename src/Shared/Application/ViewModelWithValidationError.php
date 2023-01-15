<?php

namespace App\Shared\Application;

use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ViewModelWithValidationError
{
    public function __construct(protected readonly ConstraintViolationListInterface $validationErrors)
    {
        if ($this instanceof ViewModel) {
            parent::__construct(HttpCode::BAD_REQUEST);
        }
    }

    public function jsonSerialize(): mixed
    {
        return [
            'errors' => array_reduce(iterator_to_array($this->getValidationErrors()), function (array $carry, ConstraintViolationInterface $item) {
                $carry[$item->getPropertyPath()] = $item->getMessage();

                return $carry;
            }, []),
        ];
    }

    public function getValidationErrors(): ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }
}
