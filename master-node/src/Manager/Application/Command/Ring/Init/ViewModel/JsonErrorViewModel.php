<?php

namespace App\Manager\Application\Command\Ring\Init\ViewModel;

use App\Manager\Domain\Exception\DomainException;
use App\Shared\Infrastructure\Http\HttpCode;
use Symfony\Component\Serializer\Annotation\Ignore;

class JsonErrorViewModel extends JsonInitLabelSlotsViewModel
{
    public function __construct(#[Ignore] private readonly DomainException $domainException)
    {
        parent::__construct(HttpCode::CONFLICT);
    }

    public function getError(): string
    {
        return $this->domainException->getMessage();
    }
}
