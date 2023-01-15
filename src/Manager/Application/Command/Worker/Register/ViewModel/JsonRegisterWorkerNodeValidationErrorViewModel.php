<?php

namespace App\Manager\Application\Command\Worker\Register\ViewModel;

use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class JsonRegisterWorkerNodeValidationErrorViewModel extends JsonRegisterWorkerNodeViewModel
{
    public function __construct(private readonly ConstraintViolationListInterface $validationErrors)
    {
        parent::__construct(HttpCode::BAD_REQUEST);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'errors' => array_reduce(iterator_to_array($this->validationErrors), function (array $carry, ConstraintViolationInterface $item) {
                $carry[$item->getPropertyPath()] = $item->getMessage();

                return $carry;
            }, []),
        ];
    }
}
