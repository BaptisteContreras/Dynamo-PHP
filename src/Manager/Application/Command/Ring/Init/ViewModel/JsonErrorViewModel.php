<?php

namespace App\Manager\Application\Command\Ring\Init\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Infrastructure\Http\HttpCode;

class JsonErrorViewModel extends JsonInitLabelSlotsViewModel
{
    public function __construct(private readonly DomainException $domainException)
    {
        parent::__construct(HttpCode::SERVER_ERROR);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'error' => $this->domainException->getMessage(),
        ];
    }
}
