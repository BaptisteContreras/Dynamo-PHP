<?php

namespace App\Shared\Application;

use App\Shared\Infrastructure\Http\HttpCode;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

trait ViewModelWithValidationError
{
    public function __construct(#[Ignore] protected readonly ConstraintViolationListInterface $validationErrors)
    {
        if ($this instanceof ViewModel) {
            parent::__construct(HttpCode::BAD_REQUEST);
        }
    }

    #[OA\Property(
        ref: '#/components/schemas/ValidationErrorList'
    )]
    public function getErrors(): array
    {
        return array_reduce(iterator_to_array($this->getValidationErrors()), function (array $carry, ConstraintViolationInterface $item) {
            $carry[] = [
                'property' => $item->getPropertyPath(),
                'message' => $item->getMessage(),
            ];

            return $carry;
        }, []);
    }

    #[Ignore]
    public function getValidationErrors(): ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }
}
